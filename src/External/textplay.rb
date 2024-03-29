#!/usr/bin/ruby

# TEXTPLAY -- A plain-text conversion tool for screenwriters

# This script works on your text in 5 phases:
# 
# Phase 1 - Allows the user to define conversion options on the command-line.
# Phase 2 - Looks for something that looks like fountain title pages,
#           and sets variables based on key/value pairs
# Phase 3 - Defines header and footer values for HTML and FDX conversion.
#           These blocks of text will be wrapped-around the transformed text
#           when the output file is generated.
# Phase 4 - Converts the input text to an internal xml markup in preparation for
#           further transformation.
# Phase 5 - Converts the internal xml markup to the markup requested by the user.
# Phase 6 - Dumps the result to STDOUT.



# NOTE: PHASE 1 - Options
# -----------------------------------------------------------------------------


require 'optparse'
require 'fcntl'

# Setup the options parser
options = {}
optparse = OptionParser.new do|opts|

	# This is the help banner, which just explains the command syntax
	opts.banner = "
Usage: textplay [options]
       textplay reads from STDIN and writes to STDOUT."

	# Help text
	options[:help] = false
	opts.on( '-h', '--help', "Display help" ) do
		options[:help] = true
	end

    # Create snippet instead of full document with headers/footers
	options[:snippet] = false
	opts.on( '-s', '--snippet', "Do not include document headers/footers" ) do
		options[:snippet] = true
	end

	# The default conversion type is HTML, if the '-f' option is set, convert to FDX
	options[:fdx] = false
	opts.on( '-f', '--fdx', "Convert to Final Draft .fdx" ) do
		options[:fdx] = true
	end

	# if the '-x' option is set use the internal xml format for export
	options[:xml] = false
	opts.on( '-x', '--xml', "Output as raw XML - for debugging" ) do
		options[:xml] = true
	end

	# if the '-d' option is set trigger diff mode
	options[:diff] = false
	opts.on( '-d', '--diff', "Assume input is a diff" ) do
		options[:diff] = true
	end

	# if the '-w' option is set wrap paragraphs
	options[:wrap] = false
	opts.on( '-w', '--wrap', "Wrap action and dialogue paragraphs" ) do
		options[:wrap] = true
	end

end

# Parse the options and remove them from ARGV
optparse.parse!


help_text = "
TEXTPLAY

    textplay [options]

    -h, --help        Display the help text
    -s, --snippet     Do not include document headers/footers
    -f, --fdx         Convert to Final Draft .fdx
    -x, --xml         Output as the internal raw XML
    -d, --diff        Assume input is a diff, generate revision marks
    -w, --wrap        Wrap action and dialogue paragraphs

By default textplay converts to a fully-formed HTML document.

Texplay is designed to be a Unix tool, thus it always reads from STDIN and
writes to STDOUT. To make a file use standard Unix redirection. For example:

    textplay < screenplay.fountain > screenplay.html

As another example, textplay has been tested extensively with
[PrinceXML](http://princexml.com). To make a PDF from a fountain document,
use this:

    textplay < screenplay.fountain | prince - screenplay.pdf


ABOUT TEXTPLAY

Textplay is a simple ruby-script (one file, no dependencies) that
converts screenplays written in Fountain (http://fountain.io)
formatted plain-text to HTML and FDX (Final Draft).

Textplay has been rigorously tested against fountain documents, but
it is not perfect, if you encounter a problem please open a github
issue: <https://github.com/olivertaylor/Textplay/issues>


USING DIFFS TO GENERATE REVISION MARKS

textplay is capable of converting diffs of fountain documents into screenplays
with revision marks.

You'll need to ensure the entire screenplay is included in the diff. By
default `diff` only includes, in the output, 3 lines of context around the
changes it detects. This can be changed with the `--unified` option. Like
this:

   $ diff --unified=999999 a.txt b.txt
   $ git-diff --unified=999999 <commit> <commit> a.txt

Unless your screenplay is over a million lines long,
this amount of context should suffice.

It is advisable, because of the way textplay handles whitespace,
to use `diff` with the following options:

    -E       Ignore changes due to tab expansion.
    -B       Ignore changes whose lines are all blank.

Just to be thorough about it, here is a complete command which would generate
an HTML screenplay with revision marks:

   $ diff -EB --unified=999999 a.txt b.txt | textplay -d

See git-diff's man page for relevant equivalents.


CONFIGURING TEXTPLAY

Using Fountain's `key:value` title-page syntax, you can control how
textplay interprets your screenplay. The following values can
be customized:

    * title (text) -- default: \"A Screenplay\"

      You can define what name textplay uses when generating files.
 
    * goldman_sluglines (on/off) -- default: on

      By default textplay interprets any line that's all-caps as a
      slugline.

    * screenbundle_comments (on/off) -- default: off

      To provide backwards-compatibility with screenbundle documents,
      textplay can interpret any line starting with 2 slahes `//` as comments.

    * font (text) -- default: \"Courier Prime\"

      By default textplay uses \"Courier Prime\"
      <http://quoteunquoteapps.com/courierprime/>
      a great alternative font for screenwriters. If you don't have that
      installed plain Courier will be used.
  
      If you'd like to specify your own font, Courier Prime and Courier
      will be used as backup fonts.

    * slugline_spacing (number of 12pt lines) -- default: 1

      By default textplay puts a single empty line above sluglines, you
      can change this to any number you want.

    * bold_sluglines (on/off) -- default: on

    * underlined_sluglines (on/off) -- default: off

    * wrap_paragraphs (on/off) -- default: off

      By default textplay takes every carriage return as intent
      (see: http://fountain.io/syntax#section-br). If you'd like textplay
      to wrap your action paragraphs, turn this option on. This option is
      particularly useful if your screenplay is under version control and
      you follow this advice: <http://rhodesmill.org/brandon/2012/one-sentence-per-line/>

      This option can also be set via a command-line flag.

    * header (text) -- empty by default

      Header information is displayed on every page, use this for
      revision numbers, dates, etc.

    * footer (text) -- empty by default

      Any information you'd like in the footer of every page can go here.


For the time being, all other key/values are preserved as meta-data in
the document, but otherwise ignored.

To set these values just define the key/values at the beginning of the
document like this:

title: Ron's Woodland Adventure
font: Courier New
goldman_sluglines: off
bold_sluglines: on

The block of key/value pairs:

  a. Must be the first non-comment thing in the document.

  b. Options CAN be wrapped in boneyard comments

  c. Options must NOT be indented

  d. The block of option key/value pairs cannot contain more than
     1 empty line. 2 empty lines will cause textplay to stop parsing
     for options.

For more details see the Fountain documentation 
<http://fountain.io/syntax#section-titlepage>.
"


# NOTE: Input / Output

# Some of this is redundant but I prefer specifying every contingency in case I need to control each step

# Check to see if anything is in STDIN
if STDIN.isatty == true

	# If nothing is there, check for --help
	if options[:help] == true
		# and send help text to less
		IO.popen("less", "w") { |f| f.puts help_text }
	else
		# otherwise display the option banner
		puts optparse	
	end
	exit(-1)

else

	# If there IS content in STDIN, read it
	text = STDIN.read

end



# ---------------------------------
# TEMPORARY CHECKING FOR FDX/DIFF
if options[:diff] == true and options[:fdx] == true
        puts "Converting diffs to FDX is not supported yet."
else
# ---------------------------------





# NOTE: PHASE 2 - Set title-page and meta info
# -----------------------------------------------------------------------------


# Before we do anything else, we must deal with diff markers and the meta blocks.
if options[:diff] == true
    # Remove diff header so the meta-tag detection doesn't get confused
    text = text.gsub(/^\-{3}.*\n\+{3}.*\n@@.*\n/, '')
    # Lines in meta-blocks that have been deleted should be immediately removed.
    # There's no need to mark them as revised since they're non-printing.
    # And deleted meta-tags should be removed before they're marked as meta-tags.
    text = text.gsub(/^-(?!-).*\n/, '')
    # Also remove additions of empty lines, so the meta-tagging stays sane
    text = text.gsub(/^\+\s*$/, '')
end

# If the first thing in the document (aside from comments) is a
# key-value pair, then enable tagging of the meta block.

# returns the fist non-comment line
l1_test = /^(?! *(#|=|\|\[\[|\/\*|\*\/)).+/.match(text)
# convert match to string
l1_test = l1_test[0]
# if the first line looks like a meta-block, set the "meta" variable
if l1_test =~ /: */
  meta = true
else
  meta = false
end

# -------------------------

# define regex for key-value pairs

multi_key = /
  # Cannot begin with spaces (that's a value)
  ^\+?(?!\ )
  # the key itself
  [\S\ ]+:\ *\n
  # the indented value - multiple lines allowed
  (\+?(\ {3,}|\t).+\n?)+
  # a single empty line is allowed - 2 empty lines ends the meta_block
  (^\+?\ *\n){0,1}
/x

single_key = /
  # Cannot begin with spaces (that's a value)
  ^\+?(?!\ )
  # the key
  ([\S\ ]+):\ *
  # the value
  ([^\n]+)\n
  # a single empty line is allowed - 2 empty lines ends the meta_block
  (^\+?\ *\n){0,1}
/x

# -------------------------

# If the first (non-comment) line looks like a meta tag...
# then assume everything that follows is a block of meta tags

if meta == true

  # Find the FIRST occurrence of what looks like a meta_block and tag it.
  # Very important that this is "sub" and not "gsub" - gsub will match
  # everything in the document that looks like meta tags - not a
  # great idea.
  text = text.sub(/(#{multi_key}|#{single_key})+/,'<meta_block>'+"\n"+'\0'+'</meta_block>'+"\n\n")

  if options[:diff] == true
      # remove the diff markers so they don't confuse anything
      text = text.gsub(/<meta_block>(.|\n)+?<\/meta_block>/){|tags|
        tags.gsub(/^\+/, '')
      }

  end

  # Identify multi-line key-value pairs...

  # search for multi-line key-value pairs - and tag with markup
  text = text.gsub(/<meta_block>(.|\n)+?<\/meta_block>/){|tags|
    tags.gsub(/#{multi_key}/, '<meta_multi>'+"\n"+'\0'+"\n"+'</meta_multi>'+"\n")
  }

  # search inside meta for keys
  text = text.gsub(/<meta_multi>(.|\n)+?<\/meta_multi>/){|tags|
    tags.gsub(/(.+): */, '<key>\1</key>')
  }

  # search inside meta-tags for values
  text = text.gsub(/<meta_multi>(.|\n)+?<\/meta_multi>/){|tags|
    tags.gsub(/( {3,}|\t)(.+)/, '<value>\2</value>')
  }

  # remove empty lines
  text = text.gsub(/<meta_multi>(.|\n)+?<\/meta_multi>/){|tags|
    tags.gsub(/^\n/, "")
  }

  # Identify single-line key-value pairs

  text = text.gsub(/<meta_block>(.|\n)+?<\/meta_block>/){|tags|
      tags.gsub(/#{single_key}/, '<meta-single><key>\1</key><value>\2</value></meta-single>'+"\n")
    }

end

# -------------------------

if meta == true

  # This sets the value of variables based on the meta tags

  # title
  title = text.scan(/<key>title<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # goldman_sluglines
  goldman_sluglines = text.scan(/<key>goldman_sluglines<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # screenbundle_comments
  screenbundle_comments = text.scan(/<key>screenbundle_comments<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # font
  font = text.scan(/<key>font<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # slugline_spacing
  slugline_spacing = text.scan(/<key>slugline_spacing<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # bold_sluglines
  bold_sluglines = text.scan(/<key>bold_sluglines<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # underlined_sluglines
  underlined_sluglines = text.scan(/<key>underlined_sluglines<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # wrap linebreaks in paragraphs
  wrap_paragraphs = text.scan(/<key>wrap_paragraphs<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # header
  header = text.scan(/<key>header<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

  # footer
  footer = text.scan(/<key>footer<\/key>\n?<value>([\s\S]+?)(?=<\/value>)/i).join

end


# -------------------------

# Set some defaults for the variables we're about to set

if title == nil or title == ""
  title = "A Screenplay"
end

if goldman_sluglines == nil or goldman_sluglines == ""
goldman_sluglines = "on"
end

if screenbundle_comments == nil or screenbundle_comments == ""
screenbundle_comments = "off"
end

if bold_sluglines == nil or bold_sluglines == ""
bold_sluglines = "on"
end

if underlined_sluglines == nil or underlined_sluglines == ""
underlined_sluglines = "off"
end

if font == nil or font == ""
font = "Courier Prime"
end

if slugline_spacing == nil or slugline_spacing == ""
slugline_spacing = "1"
end

# the command-line flag overrides the in-document setting
if options[:wrap] == true
    wrap_paragraphs = "on"
else
    if wrap_paragraphs == nil or wrap_paragraphs == ""
    wrap_paragraphs = "off"
    end
end

if header == nil or header == ""
header = ""
end

if footer == nil or footer == ""
footer = ""
end

# -------------------------

# Now convert the variable values to CSS for direct-insertion into the CSS

if bold_sluglines == "on"
  bold_sluglines = "bold"
else
  bold_sluglines = "normal"
end

if underlined_sluglines == "on"
  underlined_sluglines = "underline"
else
  underlined_sluglines = "none"
end

if wrap_paragraphs == "on"
  wrap_paragraphs = "normal"
else
  wrap_paragraphs = "pre-wrap"
end

# -------------------------




# NOTE: PHASE 3 - Set HTML, XML, etc. header and footers
# -----------------------------------------------------------------------------


# HTML page structure and CSS
htmlStart = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\"
\"http://www.w3.org/TR/html4/strict.dtd\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<title>#{title}</title>
<meta name=\"generator\" content=\"Textplay\">
<style type=\"text/css\" media=\"all\">

/* ---------- PAGE STYLES ---------- */

/* all page margins are maximums */
@page {
size: 8.5in 11in;
margin-top:1in;
margin-right:1in;
margin-bottom:.5in;
margin-left:1.5in;
}
/* This makes the page-counter start on the first page of the screenplay */
div#screenplay {
counter-reset: page 1;
page: Screenplay;
prince-page-group: start;
}
@page Screenplay {
/* Page Numbers */
@top-right-corner {
font: 12pt \"#{font}\", courier, monospace;
content: counter(page)\".\";
vertical-align: bottom;
padding-bottom: 1em;
}
/* Define Header */
@top-left {
content: \"\";
font: italic 10pt Georgia;
color: #888;
vertical-align: bottom;
padding-bottom: 1.3em;
}
/* Define Footer */
@bottom-left {
content: \"\";
font: italic 10pt Georgia;
color: #888;
vertical-align:top;
padding-top:0;
}
}
/* removes the header and page-numbers from the first page */
@page Screenplay:first {
@top-right-corner { content: normal; }
@top-left { content: normal; }
}
/* These control where page-breaks can and cannot happen */
p {
orphans: 2;
widows: 2;
}
dl {
page-break-inside:avoid;
}
dt, h2, h5 {
page-break-after: avoid;
}
dd.parenthetical {
orphans: 3;
widows: 3;
page-break-before: avoid;
page-break-after: avoid;
}
dd {
page-break-before:avoid;
}
div.page-break {
page-break-after:always;
}
h3 {
page-break-before: avoid;
}
/* by default Prince bookmarks all headings, no thanks */
h3, h4, h5, h6 {
prince-bookmark-level: none;
}

/* ---------- COMMON LAYOUT ---------- */

:lang(jp) {
font-family: osaka;
}

body {
font-family: \"#{font}\", courier, monospace;
font-size: 12pt;
line-height: 1;
}
#screenplay {
width: 6in;
margin:0 auto;
}
p.center {
text-align:center;
margin-left:0;
width:90%;
}
p {
margin-top:12pt;
margin-bottom:12pt;
margin-left:0;
padding-right:.25in;
width:6in;
white-space: #{wrap_paragraphs};
}

/*Character Names*/

dt {
font-weight:normal;
margin-top:1em;
margin-left:2in;
padding-right:.25in;
width:4in;
}

/*Parentheticals*/

dd.parenthetical {
margin-left:1.6in;
text-indent:-.12in;
width: 2in;
padding-right:2.66in;
}

/*Dialogue*/

dd {
margin:0;
margin-left: 1in;
width: 3.5in;
padding-right:1.75in;
line-height: inherit;
white-space: #{wrap_paragraphs};
}

/* Dual-Dialogue-blocks */

div.dialogue_wrapper {
overflow:auto;
width:100%;
}

dl.dual {
width:2.9in;
}

dl.dual dt, dl.dual dd {
margin-left:0;
width:2.5in;
padding-right:.25in;
}

dl.dual.first {
float:left;
margin-top:-12pt;
}

dl.dual.second {
margin-right:0;
margin-left:auto;
}

dl.dual dt {
text-align:center;
}

dl.dual dd.parenthetical {
width:2.1in;
margin-left:.32in;
}

/* Lyrics */
span.lyric {
font-style: italic;
}

span.lyric i {
font-style: normal;
}


/* Sluglines and Transitions */

h1,h2,h3,h4,h5,h6 {
font-weight: normal;
font-size: 12pt;
margin-top: 1em;
margin-bottom: 1em;
padding-right:.25in;
text-transform:uppercase;
}

/* Full Sluglines */

h2 {
width: inherit;
margin-top: #{slugline_spacing}em;
margin-bottom: 12pt;
margin-left: 0;
text-decoration: #{underlined_sluglines};
font-weight: #{bold_sluglines};
}

/* Right Transitions */

h3 {
margin-left: 4in;
width: 2in;
}

/* Left Transitions */

h4 {

}

/* Goldman Sluglines */

h5 {
margin-top: #{slugline_spacing}em;
font-weight: #{bold_sluglines};
text-decoration: #{underlined_sluglines};
}

span.underline {
text-decoration:underline;
}
.comment {
display:none
}

.revised { background:rgba(255, 255, 0, 0.2); }

</style>
</head>
<body>

<div id=\"screenplay\">
"

# HTML footer
htmlEnd = '
</div><!-- end screenplay -->
</body>
</html>
'

# Final Draft's XML header
fdxStart = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
<FinalDraft DocumentType="Script" Template="No" Version="1">
<Content>
'
# Final Draft's XML footer
fdxEnd = '</Content>
</FinalDraft>
'

# XML Header
xmlStart = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
<root>'

# XML Footer
xmlEnd = '</root>'




# NOTE: PHASE 4 - Convert input to XML
# -----------------------------------------------------------------------------


# Remove any DOS-style line endings:
text = text.gsub(/\r\n/, "\n")

# Remove newlines from Boneyards
text = text.gsub(/\/\*(.|\n)+?\*\//x){|boneyard|
    boneyard.gsub(/\n+/, ' ')
}

# Remove newlines from Notes
text = text.gsub(/\[{2}[^\]]+\]{2}/x){|note|
    note.gsub(/\n+/, ' ')
}

# Encode angle brackets
#text = text.gsub(/</, '{<}')
#text = text.gsub(/>/, '{>}')

# Unfortunately, when you add a note/comment to a line, but don't otherwise
# change it, textplay will still mark the line as revised because `diff` marks
# those lines as revised before textplay even gets to work.
# To properly avoid this textplay would have to remove notes/comments
# BEFORE `diff` compares the files - which is more trouble than its worth.


# Convert the diff formatting to something textplay can parse into XML
if options[:diff] == true
    # Now that notes/comments have been removed, remove diff-added empty lines
    text = text.gsub(/^\+\s*$/, '')
    # Mark revised lines
    text = text.gsub(/^\+(?!\+)([^\n]+)(\n?)/, '\1' + "{{%}}" + '\2')
    # Remove space in front of unchanged lines
    text = text.gsub(/^ (.*\n)/, '\1')
end

# Misc Encoding
text = text.gsub(/^[ \t]*([=-]{3,})[ \t]*({{%}})?$/, '<page-break />')
text = text.gsub(/&/, '&#38;')
text = text.gsub(/([^-])--([^-])/, '\1&#8209;&#8209;\2')
text = text.gsub(/^[ \t]+$/, '')

# -------- fountain escapes

# Action escape
text = text.gsub(/^\!(.+)/, '<action>\1</action>')

# Fountain Rules
text = text.gsub(/^[\ \t]*>[ ]*(.+?)[ ]*<[\ \t]*({{%}})?$/, '<center>\1\2</center>')
text = text.gsub(/^[\ \t]*\>[ \t]*(.*)$/,'<transition>\1</transition>')
text = text.gsub(/^\.(?!\.)[\ \t]*(.*)$/, '<slug>\1</slug>')
text = text.gsub(/\\\*/, '&#42;')

# Strip-out Fountain Sections and Synopses
text = text.gsub(/^[ \t]*#+[ \t]*(.*)/, "\n" + '<note>\1</note>' + "\n")
text = text.gsub(/^[ \t]*=[ \t]*(.*)/, "\n" + '<note>\1</note>' + "\n")
# these need not be completely removed simply because they do not span multiple lines

if screenbundle_comments == "on"
  # Textplay/Screenbundle comments
  text = text.gsub(/^[ \t]*\/\/\s?(.*)$/,  "\n" + '<note>\1</note>' + "\n")  
end

# And since Sections, Synopses, and Screenbundle  are non-printing,
# remove any included revision marks
text = text.gsub(/({{%}})?(<\/note>)/,'\2')

# Boneyard
text = text.gsub(/\/\* (.+) \*\//, '<note> \1 </note>')

# Note
text = text.gsub(/\[{2}(.+)\]{2}/, '<note> \1 </note>')

# -------- Transitions
# Left-Transitions
text = text.gsub(/
  # Require preceding empty line or beginning of document
  (^[\ \t]* \n | \A)
  # 1 or more words, a space
  ^[\ \t]* (  \w+(?:\ \w+)* [\ ]
  # One of these words
  (UP|IN|OUT|BLACK|WITH)  (\ ON)?
  # Ending with transition punctuation
  ([\.\:][\ ]*)
  # and optional revision marker
  ({{%}})?  )\n
  # trailing empty line
  ^[\ \t]*$
/x, "\n"+'<transition>\2</transition>'+"\n")

# Right-Transitions
text = text.gsub(/
# Require preceding empty line or beginning of document
  (^[\ \t]* \n | \A)
# 1 or more words, a space
  ^[\ \t]* (  \w+(?:\ \w+)* [\ ]
# The word "TO"
  (TO)
# Ending in a colon, optional revision mark
  (\:)  ({{%}})?  $)\n
  # trailing empty line
  ^[\ \t]*$
/x, "\n"+'<transition>\2</transition>'+"\n")


# ------- Dialogue

# The 2 search/replaces below, Standard and Escaped dialogue blocks,
# wrap the entire dialogue block in a wrapper. This allows much simpler
# regular expressions inside those blocks. 

# ESCAPED DIALOGUE BLOCKS
text = text.gsub(/
# Require preceding empty line
^[\ \t]* \n
# Character Name
^\@(.+)\n
# Dialogue
(^[\ \t]* .+ \n)+
# Require trailing empty line or end of document
(^[\ \t]*$|\Z)
/x, "\n"+'<dialogue>'+'\0'+'</dialogue>'+"\n")


# NON-ESCAPED DIALOGUE-BLOCKS
text = text.gsub(/
# Require preceding empty line
^[\ \t]* \n
# Character Name + (Note)
^[\ \t]*[^a-z\n\t]+(\ *\(.+\))?
# Optional revision marker
({{%}})?\n
# Dialogue
(^[\ \t]* .+ \n)+
# Require trailing empty line or end of document
(^[\ \t]*$|\Z)
/x, "\n"+'<dialogue>'+'\0'+'</dialogue>'+"\n")

# Now that they're wrapped, tag the individual elements

# SEARCH THE DIALOGUE-BLOCK FOR ESCAPED CHARACTERS
text = text.gsub(/<dialogue>\n(.|\n)+?<\/dialogue>/x){|character|
    character.gsub(/(<dialogue>\n)[\ \t]*\@(.+)(?=\n)/, '\1<character>\2</character>')
}

# SEARCH THE DIALOGUE-BLOCK FOR NON-ESCAPED CHARACTERS
text = text.gsub(/<dialogue>\n(.|\n)+?<\/dialogue>/x){|character|
    character.gsub(/
      # beginning tag
      (<dialogue>\n)
      # Optional indentation
      [\ \t]*
      # The all-uppercase character name
      ([^a-z\n\t]+
      # Optional (note), case ignored
      (\ *\(.+\))?)
      # Optional revision marker
      ({{%}})?
      # with a newline ahead of it
      (?=\n)
      /x, '\1<character>\2\4</character>')
}

# SEARCH THE DIALOGUE-BLOCK FOR PARENTHETICALS
text = text.gsub(/<dialogue>\n(.|\n)+?<\/dialogue>/x){|paren|
    paren.gsub(/^[ \t]*(\([^\)]+\))[ \t]*({{%}})?(?=\n)/, '<paren>\1\2</paren>')
}

# SEARCH THE DIALOGUE-BLOCK FOR DIALOGUE
text = text.gsub(/<dialogue>\n(.|\n)+?<\/dialogue>/x){|talk|
    talk.gsub(/^[ \t]*(?! )([^<\n]+)$/, '<talk>\1</talk>')
}

# SEARCH THE DIALOGUE-BLOCK FOR LYRICS
text = text.gsub(/(<talk>)~(.+)(<\/talk>)/, '\1<lyric>\2</lyric>\3')


# Dual Dialogue Blocks
# --------------------

# Add a "join-marker" above the second character
# in a block of dual dialogue. The one with a fountain '^'
text = text.gsub(/^\n(<dialogue>\n<character>.+?)( *\^)({{%}})?(<\/character>)/, '<join-marker>' + "\n" + '\1\3\4')

# wrap dual dialogue in wrapper
text = text.gsub(/\n\n(<dialogue>\n(?:(?:.+\n)+))(<join-marker>)((?:\n.+)+)/, "\n\n" + '<wrap>' + "\n" + '\1\2\3' "\n" + '</wrap>')

# remove the <join-marker> and add "class" to <dialogue> tag
text = text.gsub(/(?<=<wrap>\n)<dialogue>\n((?:.+\n)+)<join-marker>\n<dialogue>/, '<dialogue class="dual first">' + "\n" + '\1' + '<dialogue class="dual second">')


# ------- Scene Headings

# FULLY-FORMED SLUGLINES
text = text.gsub(/
# Require leading empty line - or the beginning of file
(?i:^\A | ^[\ \t]* \n)
# Respect leading whitespace
^[\ \t]*
# Standard prefixes, allowing for bold-italic
((?:[\*\_])*(i\.?\/e|int\.?\/ext|ext|int|est)
# A separator between prefix and location
(\ +|\.\ ?).*) \n
# Require trailing empty line
^[\ \t]* \n
/xi, "\n"+'<sceneheading>\1</sceneheading>'+"\n\n")

if goldman_sluglines == "on"
  # GOLDMAN SLUGLINES
  text = text.gsub(/
  # Require leading empty line - or the beginning of file
  (?i:^\A | ^[\ \t]* \n)
  # Any line with all-uppercase
  ^[ \t]*(?=\S)([^a-z\<\>\n]+)\n
  # Require trailing empty line
  ^[\ \t]* \n
  /x, "\n"+'<slug>\1</slug>'+"\n\n")
end

# ------- Misc

# Any untagged paragraph gets tagged as 'action'
text = text.gsub(/^([^\n\<].*)/, '<action>\1</action>')

# Bold, Italic, Underline
text = text.gsub(/([ \t\-_:;>])\*{3}([^\*\n]+)\*{3}(?=[ \t\)\]<\-_&;:?!.,])/, '\1<b><i>\2</i></b>')
text = text.gsub(/([ \t\-_:;>])\*{2}([^\*\n]+)\*{2}(?=[ \t\)\]<\-_&;:?!.,])/, '\1<b>\2</b>')
text = text.gsub(/([ \t\-_:;>])\*{1}([^\*\n]+)\*{1}(?=[ \t\)\]<\-_&;:?!.,])/, '\1<i>\2</i>')
text = text.gsub(/([ \t\-\*:;>])\_{1}([^\_\n]+)\_{1}(?=[ \t\)\]<\-\*&;:?!.,])/, '\1<u>\2</u>')

# ------- Japanese Characters
text = text.gsub(/[\u4e00-\u9faf]+/x, '<span lang="jp">\0</span>')

# ---------- Cleanup

# This cleans up line-breaks within dialogue blocks
text = text.gsub(/<\/talk>[ \t]*(\n)[ \t]*<talk>/,'\1')

# This cleans up action paragraphs with line-breaks.
text = text.gsub(/<\/action>[ \t]*(\n)[ \t]*<action>/,'\1')

# Convert tabs to spaces within action
text = text.gsub(/<action>(.|\n)+?<\/action>/x){|tabs|
	tabs.gsub(/\t/, '    ')
}

# cleanup extra newlines around notes
text = text.gsub(/^\n\n(<note>)/, '\1')
text = text.gsub(/(<\/note>\n\n)\n/, '\1')


# Next up, convert the revision markers {{%}}
# into xml markup.
if options[:diff] == true
    # search for {{%}} inside XML tags and replace with markup
    text = text.gsub(/
        # first, find the XML tags
        (<(transition|sceneheading|slug|center|dialogue|character|paren|talk|action)>)
        # Then the content + revision marker
        (.+) {{%}}
        # with a matching end-tag
        (<\/\2>)
    /x, '\1<revised>\3</revised>\4')
end

# Decode raw angle brackets
#text = text.gsub(/{<}/, '&#60;')
#text = text.gsub(/{>}/, '&#62;')




# NOTE: PHASE 5 - Convert XML to requested format
# -----------------------------------------------------------------------------


# And here we markup the text according to the set options

# Final Draft formatting
if options[:fdx] == true
  text = text.gsub(/<meta_block>(.|\n)+?<\/meta_block>/, '')
  text = text.gsub(/<note>/, '<Paragraph><ScriptNote><Text>')
  text = text.gsub(/<\/note>/, '</Text></ScriptNote></Paragraph>')
  text = text.gsub(/<b>/, '<Text Style="Bold">')
  text = text.gsub(/<\/b>/, '</Text>')
  text = text.gsub(/<u>/, '<Text Style="Underline">')
  text = text.gsub(/<\/u>/, '</Text>')
  text = text.gsub(/<i>/, '<Text Style="Italic">')
  text = text.gsub(/<\/i>/, '</Text>')
  text = text.gsub(/<page-break \/>/, '<Paragraph Type="Action" StartsNewPage="Yes"><Text></Text></Paragraph>')
  text = text.gsub(/<transition>/, '<Paragraph Type="Transition"><Text>')
  text = text.gsub(/<\/transition>/, '</Text></Paragraph>')
  text = text.gsub(/<(sceneheading|slug)>/, '<Paragraph Type="Scene Heading"><Text>')
  text = text.gsub(/<\/(sceneheading|slug)>/, '</Text></Paragraph>')
  text = text.gsub(/<center>/, '<Paragraph Type="Action" Alignment="Center"><Text>')
  text = text.gsub(/<\/center>/, '</Text></Paragraph>')
  text = text.gsub(/<\/?dialogue>/,'')
  text = text.gsub(/<character>/, '<Paragraph Type="Character"><Text>')
  text = text.gsub(/<\/character>/, '</Text></Paragraph>')
  text = text.gsub(/<paren>/, '<Paragraph Type="Parenthetical"><Text>')
  text = text.gsub(/<\/paren>/, '</Text></Paragraph>')
  text = text.gsub(/<talk>/, '<Paragraph Type="Dialogue"><Text>')
  text = text.gsub(/<\/talk>/, '</Text></Paragraph>')
  text = text.gsub(/<action>/, '<Paragraph Type="Action"><Text>')
  text = text.gsub(/<\/action>/, '</Text></Paragraph>')
elsif options[:xml] == true
  text = text
else
  # default HTML formatting  
  text = text.gsub(/<note>/, '<p class="comment">')
  text = text.gsub(/<\/note>/, '</p>')
  text = text.gsub(/<meta_block>(.|\n)+?<\/meta_block>/, '<!--\0-->')
  text = text.gsub(/<page-break \/>/, '<div class="page-break"></div>')
  text = text.gsub(/<transition>/, '<h3 class="right-transition">')
  text = text.gsub(/<\/transition>/, '</h3>')
  text = text.gsub(/<sceneheading>/, '<h2 class="full-slugline">')
  text = text.gsub(/<\/sceneheading>/, '</h2>')
  text = text.gsub(/<slug>/, '<h5 class="goldman-slugline">')
  text = text.gsub(/<\/slug>/, '</h5>')
  text = text.gsub(/<center>/, '<p class="center">')
  text = text.gsub(/<\/center>/, '</p>')
  text = text.gsub(/<wrap>/, '<div class="dialogue_wrapper">')
  text = text.gsub(/<\/wrap>/, '</div>')
  text = text.gsub(/<dialogue( class="dual (first|second)")?>/,'<dl\1>')
  text = text.gsub(/<\/dialogue>/,'</dl>')
  text = text.gsub(/<character>/, '<dt class="character">')
  text = text.gsub(/<\/character>/, '</dt>')
  text = text.gsub(/<paren>/, '<dd class="parenthetical">')
  text = text.gsub(/<\/paren>/, '</dd>')
  text = text.gsub(/<talk>/, '<dd class="dialogue">')
  text = text.gsub(/<\/talk>/, '</dd>')
  text = text.gsub(/<lyric>/, '<span class="lyric">')
  text = text.gsub(/<\/lyric>/, '</span>')
  text = text.gsub(/<action>/, '<p class="action">')
  text = text.gsub(/<\/action>/, '</p>')
  text = text.gsub(/<revised>/, '<span class="revised">')
  text = text.gsub(/<\/revised>/, '</span>')
end




# NOTE: PHASE 6 - Output
# -----------------------------------------------------------------------------


if options[:fdx] == true
  puts fdxStart
  puts text
  puts fdxEnd
elsif options[:xml] == true
# output xml if requested
  if options[:snippet] == false
  puts xmlStart
  end
  puts text
  if options[:snippet] == false
  puts xmlEnd
  end
else
# otherwise fallback to HTML
  if options[:snippet] == false
  puts htmlStart
  end
  puts text
  if options[:snippet] == false
  puts htmlEnd
  end
end


# ---------------------------------
# TEMPORARY CHECKING FOR FDX/DIFF
end
# ---------------------------------