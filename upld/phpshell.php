<?php
/////////////////////////////////////////////////////////
//Google Hack Honeypot v1.1
//Template File
//http://ghh.sourceforge.net - many thanks to SourceForge
/////////////////////////////////////////////////////////
//Copyright (C) 2005 GHH Project
//
//This program is free software; you can redistribute it and/or modify 
//it under the terms of the GNU General Public License as published by 
//the Free Software Foundation; either version 2 of the License, or 
//(at your option) any later version.
//
//This program is distributed in the hope that it will be useful, 
//but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
//or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
//for more details.
//
//You should have received a copy of the GNU General Public License along 
//with this program; if not, write to the 
//Free Software Foundation, Inc., 
//59 Temple Place, Suite 330, 
//Boston, MA 02111-1307 USA
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Begin Configuration Section
////////////////////////////////////////////////////////

//Enter the path to the GHH global configuration file
//(a smart move would be to change the config.php filename.)
$ConfigFile = '';

//Enter the URL of the page that links to this honeypot. This will help detect false positives 
//where a user finds your transparent link.
//(I.E http://yourdomain.com/forums/index.php, Wherever you put your transparent link to the honeypot.)
$SafeReferer = '';

//The Honeypot will appear to run under this username.
$Username = ''; 

////////////////////////////////////////////////////////
//End Configuration Section
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Housekeeping Section
//Include config, disable the header protection, init variables, stealth the errors.
////////////////////////////////////////////////////////
error_reporting(0);
$Honeypot = true;
include($ConfigFile);
////////////////////////////////////////////////////////
//End housekeeping section
////////////////////////////////////////////////////////

//Attack Acquisition Section
$Attack = getAttacker();

//Determine Standard Signatures
$Signature = standardSigs($Attack, $SafeReferer);

////////////////////////////////////////////////////////
//Begin Custom Honeypot Section
//GHH Honeypot by Brian Engert, Ryan McGeehan for GHDB Signature #365 (intitle:"PHP Shell *" "Enable stderr" filetype:php)
////////////////////////////////////////////////////////
$HoneypotName = "PHPSHELL";

//Beginning Shell Emulation
$output= '';
if(isset($_POST['command']))
{
	//they sent us a command so let's look for ; then emulate it 
	$command = $_POST['command'];
	$commands = explode(";", $command);
	//echo "command = $command <br>";//debug code
	foreach ($commands as $cmd)
	{
		//now I have each command with it's paramaters in a seperate string in the commands array
		$space = strpos($cmd, " ");	//if this space is inside of quotes we want to keep looking
		if (strcmp($space,"") == 0)
           		 $space = strlen($cmd);//we don't have a space so make the "space" the end of the string
	   
	   	$myCommand = substr($cmd, 0, $space);
		$paramaters = substr($cmd, $space+1, strlen($cmd));
		$output .= runCommand($myCommand, $paramaters, $Username)."\n";\
	}
}

//get the url up to the first ? so we have some proper links, prevent proxied attacks
$ourfile = $_SERVER['REQUEST_URI'];
$question = strpos($ourfile, '?');
if (strcmp($question,"") == 0)
	$question =strlen($ourfile);
$ourfile = substr($ourfile, 0, $question);


//Trick PHP Shell page
echo <<< Heredoc
<html>
<head>
<title>PHP Shell 1.7</title>
</head>
<body>
<h1>PHP Shell 1.7</h1>


<form name="myform" action="{$ourfile}" method="post">
<p>Current working directory: <b>
<a href="{$ourfile}?work_dir=/">Root</a>/</b></p>

<p>Choose new working directory:
<select name="work_dir" onChange="this.form.submit()">
<option value="/" selected>Current Directory</option>
<option value="/">Parent Directory</option>
<option value="/home">home</option>

</select></p>

<p>Command: <input type="text" name="command" size="60">

<input name="submit_btn" type="submit" value="Execute Command"></p>

<p>Enable <code>stderr</code>-trapping? <input type="checkbox" name="stderr"></p>
<textarea cols="80" rows="20" readonly>

Heredoc;

echo $output;

echo <<< Heredoc
</textarea>
</form>

<script language="JavaScript" type="text/javascript">
document.forms[0].command.focus();
</script>

<hr>
<i>Copyright &copy; 2000&ndash;2002, <a
href="mailto:gimpster@gimpster.com">Martin Geisler</a>. Get the latest
version at <a href="http://www.gimpster.com">www.gimpster.com</a>.</i>

</body>
</html>

Heredoc;

//View Commands Hacker is running
if(isset($_POST['command']))
	$Signature[] = $_POST['command'];
	
//Find our PHP shell target in the referer site
if (strstr($Attack['referer'], "Shell")){
	 $Signature[] = "Target in URL";
}

//Finds if exact GHDB signature was used
if (strstr ($Attack['referer'], "intitle%3A%22PHP+Shell+*%22+%22Enable+stderr%22+filetype%3Aphp")){
	 $Signature[] = "GHDB Signature!";
}

//"Execute" commands like id, uname, wget. See associated functions below
function runCommand($command, $paramaters, $Username) {

  if(strcmp($command, 'id') == 0 || strcmp("/usr/bin/id", $command) == 0){
  	$output = id($paramaters);
  }else if(strcmp($command, 'uname') == 0 || strcmp("/bin/uname", $command) == 0){
	return uname($paramaters);
  }else if(strcmp($command, 'wget') == 0 || strcmp("/usr/bin/wget", $command) == 0){
	$output = wget($paramaters);
  }else if (strcmp($command, 'w') == 0 || strcmp("/usr/bin/w", $command) == 0){
    $output = w($paramaters, $Username);
  }else if(strcmp($command, 'whoami') == 0 || strcmp("/usr/bin/whoami", $command) == 0){
	$output = whoami($paramaters, $Username);
  }else if(strcmp($command, 'pwd') == 0 || strcmp("/bin/pwd", $command) == 0){
	if (strstr($paramaters, '-'))
		$output = "-bash: pwd: $paramaters: invalid option\npwd: usage: pwd [-PL]";
	else
	    $output = "/home/$Username/htdocs";
  }else if(strcmp("ps", $command) == 0 || strcmp("/bin/ps", $command) == 0){
    $output = "  PID TTY       TIME COMMAND\n16919 pts/0     0:00 bash";
  }else if(strcmp("cat", $command) == 0){
	$output = cat($paramaters, $Username);
  }else if(strcmp("ls", $command) == 0 || strcmp("/bin/ls", $command) == 0){
  	$output = ls($paramaters);
  }else if(strcmp("ping", $command) == 0 || strcmp("/bin/ping", $command) == 0){
        $output = ping($paramaters);
  }else if(strcmp("/bin/echo", $command) == 0 || strcmp("echo", $command) == 0){
        $output = descapeQuotes($paramaters);
  }else if(strcmp("/bin/bash", $command) == 0 || strcmp("bash", $command) == 0){
        $output = "";
  }else if(strcmp("uptime", $command) == 0 || strcmp("/usr/bin/uptime", $command) == 0){
	$output = uptime($paramaters);
  }else{
    $output = ""; //the real phpshell does not give bash errors
  }
  return $output;
}
function descapeQuotes($string) {
    $string = preg_replace('/[^\\\\]([\'"])/', '', $string); //remove non escaped ' and "
    $string = preg_replace('/\\\\(\')/', '\'', $string); //replace escaped ' and " with just the char that's being escaped
    $string = preg_replace('/\\\\(\")/', '"', $string); //replace escaped ' and " with just the char that's being escaped
    return $string;
}
function whoami($paramaters, $Username)
{
	if (strstr($paramaters, '--help'))
		$output = <<<whoamidump
Usage: whoami [OPTION]...
Print the user name associated with the current effective user id.
Same as id -un.

      --help     display this help and exit
      --version  output version information and exit

Report bugs to <bug-coreutils@gnu.org>.
whoamidump;
	else if (strstr($paramaters, '--version'))
		$output = <<<moreversions
whoami (GNU coreutils) 5.2.1
Written by Richard Mlynarik.

Copyright (C) 2004 Free Software Foundation, Inc.
This is free software; see the source for copying conditions.  There is NO
warranty; not even for MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
moreversions;
	else if (strcmp($paramaters, '') == 0)
  		$output = $Username;
	else
		$output = "Try `whoami --help' for more information.";
	return $output; 
}
function uptime($paramaters)
{
	$time = date("g:ia");
	$load1 = rand(35,60)/100; 
	$load2 = rand(35,60)/100;
	$load3 = rand(35,60)/100;
	$uptime = date("z") + rand(1,20);  //today plus 50 days
	
	if (strstr($paramaters, '-V'))
		$output = "procps version 3.2.1";
	else if (strcmp($paramaters, '') == 0)
		$output = $time." up ".$uptime." days, 15:24, 1 user, load averages: ".$load1.", ".$load2.", ".$load3."";
	else
		$output = "usage: uptime [-V]\n    -V    display version";
	return $output;
}
function cat($paramaters, $Username)
{
  	if (strstr($paramaters, '--help'))
		$output = "Usage: cat [OPTION] [FILE]...
Concatenate FILE(s), or standard input, to standard output.

  -A, --show-all           equivalent to -vET
  -b, --number-nonblank    number nonblank output lines
  -e                       equivalent to -vE
  -E, --show-ends          display $ at end of each line
  -n, --number             number all output lines
  -r, --reversible         use \ to make the output reversible, implies -v
  -s, --squeeze-blank      never more than one single blank line
  -t                       equivalent to -vT
  -T, --show-tabs          display TAB characters as ^I
  -u                       (ignored)
  -v, --show-nonprinting   use ^ and M- notation, except for LFD and TAB
      --help     display this help and exit
      --version  output version information and exit

With no FILE, or when FILE is -, read standard input.

Report bugs to <bug-coreutils@gnu.org>.";
	else if (strstr($paramaters, '--version'))
		$output = "cat (coreutils) 5.2.1\nWritten by Torbjorn Granlund and Richard M. Stallman.\n\nCopyright (C) 2004 Free Software Foundation, Inc.\nThis is free software; see the source for copying conditions.  There is NO\nwarranty; not even for MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.";
	else if (strstr($paramaters, '>') || strstr($paramaters, '<'))
		$output = "";
	else if (strstr($paramaters, '/etc/passwd'))
		$output = "root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/bin/sh
bin:x:2:2:bin:/bin:/bin/sh
sys:x:3:3:sys:/dev:/bin/sh
sync:x:4:65534:sync:/bin:/bin/sync
games:x:5:60:games:/usr/games:/bin/sh
man:x:6:12:man:/var/cache/man:/bin/sh
lp:x:7:7:lp:/var/spool/lpd:/bin/sh
mail:x:8:8:mail:/var/mail:/bin/sh
news:x:9:9:news:/var/spool/news:/bin/sh
uucp:x:10:10:uucp:/var/spool/uucp:/bin/sh
proxy:x:13:13:proxy:/bin:/bin/sh
www-data:x:33:33:www-data:/var/www:/bin/sh
backup:x:34:34:backup:/var/backups:/bin/sh
list:x:38:38:Mailing List Manager:/var/list:/bin/sh
irc:x:39:39:ircd:/var/run/ircd:/bin/sh
gnats:x:41:41:Gnats Bug-Reporting System (admin):/var/lib/gnats:/bin/sh
nobody:x:65534:65534:nobody:/nonexistent:/bin/sh
Debian-exim:x:102:102::/var/spool/exim4:/bin/false
identd:x:100:65534::/var/run/identd:/bin/false
sshd:x:101:65534::/var/run/sshd:/bin/false
bind:x:103:104::/var/cache/bind:/bin/false
postfix:x:104:105::/var/spool/postfix:/bin/false
mysql:x:105:107:MySQL Server,,,:/var/lib/mysql:/bin/false
$Username:x:1000:1000:,,,:/home/$Username:/bin/bas";

	else if (strstr($paramaters, '/etc/hosts'))
		$output = "127.0.0.1       localhost.localdomain   localhost

# The following lines are desirable for IPv6 capable hosts
::1     ip6-localhost ip6-loopback
fe00::0 ip6-localnet
ff00::0 ip6-mcastprefix
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
ff02::3 ip6-allhosts
";
	else if (strstr($paramaters, '/proc/cpuinfo'))
		$output = "processor       : 0
vendor_id       : AuthenticAMD
cpu family      : 6
model           : 8
model name      : AMD Athlon(tm) XP 1800+
stepping        : 1
cpu MHz         : 1515.458
cache size      : 256 KB
fdiv_bug        : no
hlt_bug         : no
f00f_bug        : no
coma_bug        : no
fpu             : yes
fpu_exception   : yes
cpuid level     : 1
wp              : yes
flags           : fpu vme de pse tsc msr pae mce cx8 apic sep mtrr pge mca cmov pat pse36 mmx fxsr sse syscall mmxext 3dnowext 3dnow
bogomips        : 3006.46
";

	else if (strstr($paramaters, 'phpshell.php'))
		$output = <<<phpshellz
<?php

define('PHPSHELL_VERSION', '1.7');

/*

  **************************************************************
  *                        PHP Shell                           *
  **************************************************************
  \$Id: phpshell.php,v 1.18 2002/09/18 15:49:54 gimpster Exp $

  PHP Shell is aninteractive PHP-page that will execute any command
  entered. See the files README and INSTALL or http://www.gimpster.com
  for further information.

  Copyright (C) 2000-2002 Martin Geisler <gimpster@gimpster.com>

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.
  
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  You can get a copy of the GNU General Public License from this
  address: http://www.gnu.org/copyleft/gpl.html#SEC1
  You can also write to the Free Software Foundation, Inc., 59 Temple
  Place - Suite 330, Boston, MA  02111-1307, USA.
  
*/
?>

<html>
<head>
<title>PHP Shell <?php echo PHPSHELL_VERSION ?></title>
</head>
<body>
<h1>PHP Shell <?php echo PHPSHELL_VERSION ?></h1>

<?php

if (ini_get('register_globals') != '1') {
  /* We'll register the variables as globals: */
  if (!empty(\$HTTP_POST_VARS))
    extract(\$HTTP_POST_VARS);
  
  if (!empty(\$HTTP_GET_VARS))
    extract(\$HTTP_GET_VARS);

  if (!empty(\$HTTP_SERVER_VARS))
    extract(\$HTTP_SERVER_VARS);
}

/* First we check if there has been asked for a working directory. */
if (!empty(\$work_dir)) {
  /* A workdir has been asked for */
  if (!empty(\$command)) {
    if (ereg('^[[:blank:]]*cd[[:blank:]]+([^;]+)$', \$command, \$regs)) {
      /* We try and match a cd command. */
      if (\$regs[1][0] == '/') {
        \$new_dir = \$regs[1]; // 'cd /something/...'
      } else {
        \$new_dir = \$work_dir . '/' . \$regs[1]; // 'cd somedir/...'
      }
      if (file_exists(\$new_dir) && is_dir(\$new_dir)) {
        \$work_dir = \$new_dir;
      }
      unset(\$command);
    }
  }
}

if (file_exists(\$work_dir) && is_dir(\$work_dir)) {
  /* We change directory to that dir: */
  chdir(\$work_dir);
}

/* We now update \$work_dir to avoid things like '/foo/../bar': */
\$work_dir = exec('pwd');

?>

<form name=\"myform\" action=\"<?php echo \$PHP_SELF ?>\" method=\"post\">
<p>Current working directory: <b>
<?php

\$work_dir_splitted = explode('/', substr(\$work_dir, 1));

echo '<a href=\"' . \$PHP_SELF . '?work_dir=/\">Root</a>/';

if (!empty(\$work_dir_splitted[0])) {
  \$path = '';
  for (\$i = 0; \$i < count(\$work_dir_splitted); \$i++) {
    \$path .= '/' . \$work_dir_splitted[\$i];
    printf('<a href=\"%s?work_dir=%s\">%s</a>/',
           \$PHP_SELF, urlencode(\$path), \$work_dir_splitted[\$i]);
  }
}

?></b></p>
<p>Choose new working directory:
<select name=\"work_dir\" onChange=\"this.form.submit()\">
<?php
/* Now we make a list of the directories. */
\$dir_handle = opendir(\$work_dir);
/* Run through all the files and directories to find the dirs. */
while (\$dir = readdir(\$dir_handle)) {
  if (is_dir(\$dir)) {
    if (\$dir == '.') {
      echo \"<option value=\\"\$work_dir\\" selected>Current Directory</option>\n\";
    } elseif (\$dir == '..') {
      /* We have found the parent dir. We must be carefull if the parent 
	 directory is the root directory (/). */
      if (strlen(\$work_dir) == 1) {
	/* work_dir is only 1 charecter - it can only be / There's no
          parent directory then. */
      } elseif (strrpos(\$work_dir, '/') == 0) {
	/* The last / in work_dir were the first charecter.
	   This means that we have a top-level directory
	   eg. /bin or /home etc... */
      echo \"<option value=\\"/\\">Parent Directory</option>\n\";
      } else {
      /* We do a little bit of string-manipulation to find the parent
	 directory... Trust me - it works :-) */
      echo \"<option value=\\"\". strrev(substr(strstr(strrev(\$work_dir), \"/\"), 1)) .\"\\">Parent Directory</option>\n\";
      }
    } else {
      if (\$work_dir == '/') {
	echo \"<option value=\\"\$work_dir\$dir\\">\$dir</option>\n\";
      } else {
	echo \"<option value=\\"\$work_dir/\$dir\\">\$dir</option>\n\";
      }
    }
  }
}
closedir(\$dir_handle);

?>

</select></p>

<p>Command: <input type=\"text\" name=\"command\" size=\"60\">
<input name=\"submit_btn\" type=\"submit\" value=\"Execute Command\"></p>

<p>Enable <code>stderr</code>-trapping? <input type=\"checkbox\" name=\"stderr\"></p>
<textarea cols=\"80\" rows=\"20\" readonly>

<?php
if (!empty(\$command)) {
  if (\$stderr) {
    \$tmpfile = tempnam('/tmp', 'phpshell');
    \$command .= \" 1> \$tmpfile 2>&1; \" .
    \"cat \$tmpfile; rm \$tmpfile\";
  } else if (\$command == 'ls') {
    /* ls looks much better with ' -F', IMHO. */
    \$command .= ' -F';
  }
  system(\$command);
}
?>

</textarea>
</form>

<script language=\"JavaScript\" type=\"text/javascript\">
document.forms[0].command.focus();
</script>

<hr>
<i>Copyright &copy; 2000&ndash;2002, <a
href=\"mailto:gimpster@gimpster.com\">Martin Geisler</a>. Get the latest
version at <a href=\"http://www.gimpster.com\">www.gimpster.com</a>.</i>
</body>
</html>
phpshellz;
	else if (strcmp($paramaters, '') == 0)
		$output = "";
	else
		$output = "cat: ".$paramaters.": No such file or directory";
	return $output;
}
function ls($paramaters)
{
	$pattern = "/\/([a-zA-Z-._]*)$/";
	preg_match($pattern, $_SERVER['REQUEST_URI'], $matches);
	//this gives me the file that should be in ls :-D
	if (strstr($paramaters, '--help'))
		$output = <<<lsmandump
Usage: ls [OPTION]... [FILE]...
List information about the FILEs (the current directory by default).
Sort entries alphabetically if none of -cftuSUX nor --sort.

Mandatory arguments to long options are mandatory for short options too.
  -a, --all                  do not hide entries starting with .
  -A, --almost-all           do not list implied . and ..
      --author               print the author of each file
  -b, --escape               print octal escapes for nongraphic characters
      --block-size=SIZE      use SIZE-byte blocks
  -B, --ignore-backups       do not list implied entries ending with ~
  -c                         with -lt: sort by, and show, ctime (time of last
                               modification of file status information)
                               with -l: show ctime and sort by name
                               otherwise: sort by ctime
  -C                         list entries by columns
      --color[=WHEN]         control whether color is used to distinguish file
                               types.  WHEN may be `never', `always', or `auto'
  -d, --directory            list directory entries instead of contents,
                               and do not dereference symbolic links
  -D, --dired                generate output designed for Emacs' dired mode
  -f                         do not sort, enable -aU, disable -lst
  -F, --classify             append indicator (one of */=@|) to entries
      --format=WORD          across -x, commas -m, horizontal -x, long -l,
                               single-column -1, verbose -l, vertical -C
      --full-time            like -l --time-style=full-iso
  -g                         like -l, but do not list owner
  -G, --no-group             inhibit display of group information
  -h, --human-readable  print sizes in human readable format (e.g., 1K 234M 2G)
      --si                   likewise, but use powers of 1000 not 1024
  -H, --dereference-command-line
                             follow symbolic links listed on the command line
      --dereference-command-line-symlink-to-dir
                             follow each command line symbolic link
                               that points to a directory
      --indicator-style=WORD append indicator with style WORD to entry names:
                               none (default), classify (-F), file-type (-p)
  -i, --inode                print index number of each file
  -I, --ignore=PATTERN       do not list implied entries matching shell PATTERN
  -k                         like --block-size=1K
  -l                         use a long listing format
  -L, --dereference          when showing file information for a symbolic
                               link, show information for the file the link
                               references rather than for the link itself
  -m                         fill width with a comma separated list of entries
  -n, --numeric-uid-gid      like -l, but list numeric UIDs and GIDs
  -N, --literal              print raw entry names (don't treat e.g. control
                               characters specially)
  -o                         like -l, but do not list group information
  -p, --file-type            append indicator (one of /=@|) to entries
  -q, --hide-control-chars   print ? instead of non graphic characters
      --show-control-chars   show non graphic characters as-is (default
                             unless program is `ls' and output is a terminal)
  -Q, --quote-name           enclose entry names in double quotes
      --quoting-style=WORD   use quoting style WORD for entry names:
                               literal, locale, shell, shell-always, c, escape
  -r, --reverse              reverse order while sorting
  -R, --recursive            list subdirectories recursively
  -s, --size                 print size of each file, in blocks
  -S                         sort by file size
      --sort=WORD            extension -X, none -U, size -S, time -t,
                               version -v
                             status -c, time -t, atime -u, access -u, use -u
      --time=WORD            show time as WORD instead of modification time:
                               atime, access, use, ctime or status; use
                               specified time as sort key if --sort=time
      --time-style=STYLE     show times using style STYLE:
                               full-iso, long-iso, iso, locale, +FORMAT
                             FORMAT is interpreted like `date'; if FORMAT is
                             FORMAT1<newline>FORMAT2, FORMAT1 applies to
                             non-recent files and FORMAT2 to recent files;
                             if STYLE is prefixed with `posix-', STYLE
                             takes effect only outside the POSIX locale
  -t                         sort by modification time
  -T, --tabsize=COLS         assume tab stops at each COLS instead of 8
  -u                         with -lt: sort by, and show, access time
                               with -l: show access time and sort by name
                               otherwise: sort by access time
  -U                         do not sort; list entries in directory order
  -v                         sort by version
  -w, --width=COLS           assume screen width instead of current value
  -x                         list entries by lines instead of by columns
  -X                         sort alphabetically by entry extension
  -1                         list one file per line
      --help     display this help and exit
      --version  output version information and exit

SIZE may be (or may be an integer optionally followed by) one of following:
kB 1000, K 1024, MB 1000*1000, M 1024*1024, and so on for G, T, P, E, Z, Y.

By default, color is not used to distinguish types of files.  That is
equivalent to using --color=none.  Using the --color option without the
optional WHEN argument is equivalent to using --color=always.  With
--color=auto, color codes are output only if standard output is connected
to a terminal (tty).

Report bugs to <bug-coreutils@gnu.org>.
lsmandump;
	else if (strstr($paramaters, '-la') || strstr($paramaters, '-al') || (strstr($paramaters, '-a') && strstr($paramaters, '-l')))
		$output = "total 16
drwxr-xr-x  2 $Username $Username    80 2005-11-30 10:37 .
drwxr-xr-x  3 $Username $Username    80 2005-11-30 10:33 ..
-rw-r--r--  1 $Username $Username 12976 2005-11-30 10:34 index.php
-rw-r--r--  1 $Username $Username 12976 2005-11-30 10:34 ".sanitize($matches[1])."
-rw-r--r--  1 $Username $Username 12976 2005-11-30 10:34 config.php
";
	else if (strstr($paramaters, '-l'))
		$output = "total 16
-rw-r--r--  1 $Username $Username 12976 2005-11-30 10:34 index.php
-rw-r--r--  1 $Username $Username 12976 2005-11-30 10:34 ".sanitize($matches[1])."
-rw-r--r--  1 $Username $Username 12976 2005-11-30 10:34 config.php
";
	else if (strcmp($paramaters, '') == 0)
		$output = "index.php	".sanitize($matches[1])."	config.php";
	else
    	$output = "ls: unrecognised option `$paramaters'\nTry `ls --help' for more information.";

	return $output;
}
function ping($paramaters)
{
	$paramater = explode(" ", $paramaters);
        foreach ($paramater as $param)
        {
            if (strstr($param, '.'))
                $domainip = $param;//this is either our domain or ip of what we are going to "Ping"
        }
        if (isset($domainip)){
            $ip = gethostbyname($domainip);
            $ttl = rand(60,120);
            $timea = rand(100,130);//if it goes below 100 it's still 3 digits so this makes it ezer
            $timeb = rand(100,130);
            $timec = rand(100,130);
            $timed = rand(100,130);
            sleep(($timeb + $timec + $timed)/1000 + 2);
            //I should do this for as meny as I'm told to do with -t but amm don't want to right now
            $output = "PING $domainip ($ip) 56(84) bytes of data.
64 bytes from $domainip ($ip): icmp_seq=1 ttl=$ttl time=$timea ms
64 bytes from $domainip ($ip): icmp_seq=2 ttl=$ttl time=$timeb ms
64 bytes from $domainip ($ip): icmp_seq=3 ttl=$ttl time=$timec ms
64 bytes from $domainip ($ip): icmp_seq=4 ttl=$ttl time=$timed ms

--- $domainip ping statistics ---
4 packets transmitted, 4 received, 0% packet loss, time 2999ms
rtt min/avg/max/mdev = $timea/$timeb/$timed/$timec ms
";

        }else {
            $output = <<<pingpong
Usage: ping [-LRUbdfnqrvVaA] [-c count] [-i interval] [-w deadline]
            [-p pattern] [-s packetsize] [-t ttl] [-I interface or address]
            [-M mtu discovery hint] [-S sndbuf]
            [ -T timestamp option ] [ -Q tos ] [hop1 ...] destination
pingpong;
        }
        return $output;
}
function w($paramaters, $Username)
{
	$time = date("g:ia");
    $load1 = rand(35,60)/100; 
    $load2 = rand(35,60)/100;
    $load3 = rand(35,60)/100;
    $uptime = date("z") + 50;  //today plus 50 days
	if (strstr($paramaters, '-V'))
		$output = "procps version 3.2.1";
	else if (strstr($paramaters, '-h')){
		if (strstr($paramaters, 'f')){
			if (strstr($paramaters, 's')){
				$output = "$Username     pts/0      0.00s -bash";
			}else{
				$output = "$Username     pts/0     15:58    0.00s  0.04s  0.01s -bash";
			}
		}else if (strstr($paramaters, 's')){
			$output = "$Username     pts/0    -                 0.00s -bash";
		}
	}else if (strstr($paramaters, '-s')){
		if (strstr($paramaters, 'f')){
			$output = " $time up 1 day, 21:05,  1 user,  load average: $load1, $load2, $load3\nUSER     TTY         IDLE WHAT\n$Username     pts/0      0.00s -bash";
		}else
			$output = " $time up $uptime day, 21:07,  1 user,  load average: $load1, $load2, $load3\nUSER     TTY      FROM               IDLE WHAT\n$Username     pts/0    -                 0.00s -bash";
	}else if (strstr($paramaters, '-f')){
		$output = " $time up $uptime day, 21:10,  1 user,  load average: $load1, $load2, $load3\nUSER     TTY        LOGIN@   IDLE   JCPU   PCPU WHAT\n$Username     pts/0     15:58    0.00s  0.04s  0.01s -bash";
	}else if (strcmp($paramaters, '') == 0)
		$output = " $time up $uptime day, 20:51,  1 user,  load average: $load1, $load2, $load3\nUSER     TTY      FROM              LOGIN@   IDLE   JCPU   PCPU WHAT\n$Username     pts/0    -                15:58    0.00s  0.03s  0.01s w";
	else
		$output = "  16:34:20 up 1 day, 21:27,  1 user,  load average: $load1, $load2, $load3\nUSER     TTY      FROM              LOGIN@   IDLE   JCPU   PCPU WHAT";
	return $output; 
}
function id($paramaters)
{
	$output = "uid=0(root) gid=0(root) groups=0(root)";
	if (strstr($paramaters, '--help'))
	{
		$output = <<<idhelp
Usage: id [OPTION]... [USERNAME]
Print information for USERNAME, or the current user.

  -a              ignore, for compatibility with other versions
  -g, --group     print only the effective group ID
  -G, --groups    print all group IDs
  -n, --name      print a name instead of a number, for -ugG
  -r, --real      print the real ID instead of the effective ID, with -ugG
  -u, --user      print only the effective user ID
      --help     display this help and exit
      --version  output version information and exit

Without any OPTION, print some useful set of identified information.

Report bugs to <bug-coreutils@gnu.org>.
idhelp;
	}
	return $output;
}
function uname($paramaters)
{
    $date = date("D M j G:i:s");
  	$output = "";
	if (strstr($paramaters, '-')){
        //the next array of if's are correct order I checked the combos and you only need 1 - at least for snrvmo
		if (strstr($paramaters, '--help'))
		{
			$output = <<<HEREDOC
Usage: uname [OPTION]...
Print certain system information.  With no OPTION, same as -s.

  -a, --all                print all information, in the following order:
  -s, --kernel-name        print the kernel name
  -n, --nodename           print the network node hostname
  -r, --kernel-release     print the kernel release
  -v, --kernel-version     print the kernel version
  -m, --machine            print the machine hardware name
  -o, --operating-system   print the operating system
      --help     display this help and exit
      --version  output version information and exit

Report bugs to <bug-coreutils@gnu.org>.
debian:/home/lart# uname --hes
uname: unrecognised option `--hes'
Try `uname --help' for more information.
HEREDOC;
			return $output;
		}
		if (strstr($paramaters, 's') || strstr($paramaters, '--kernel-name'))
			$output .= "Linux ";
		if (strstr($paramaters, 'n') || strstr($paramaters, '--nodename'))
			$output .= "debian ";
		if (strstr($paramaters, 'r') || strstr($paramaters, '--kernel-release'))
			$output .= "2.6.8-2-k7 ";
		if (strstr($paramaters, 'v') || strstr($paramaters, '--kernel-version'))
			$output .= "#1 $date ";
		if (strstr($paramaters, 'm') || strstr($paramaters, '--machine'))
			$output .= "i686 ";
		if (strstr($paramaters, 'o') || strstr($paramaters, '--operating-system'))
			$output .= "GNU/Linux";
		if (strstr($paramaters, 'a') || strstr($paramaters, '--all'))
			$output .= "Linux debian 2.6.8-2-k7 #1 $date i686 GNU/Linux";
		if (strlen($output) == 0)
		{
			$output = <<<badparam
uname: unrecognised option `$paramaters'
Try `uname --help' for more information.
badparam;
		}
	}else
		$output = "Linux";
	return $output;
}
function wget($paramaters)
{
	$time = date("g:ia");
    $size = rand(5120,1048576); 
	$speed = rand(30,500);
    $datetime = date("m-d-Y.G-i-s");
    
    if (strstr($paramaters, '--help')){
		$output = "GNU Wget 1.9.1, a non-interactive network retriever.
Usage: wget [OPTION]... [URL]...

Mandatory arguments to long options are mandatory for short options too.

Startup:
  -V,  --version           display the version of Wget and exit.
  -h,  --help              print this help.
  -b,  --background        go to background after startup.
  -e,  --execute=COMMAND   execute a `.wgetrc'-style command.

Logging and input file:
  -o,  --output-file=FILE     log messages to FILE.
  -a,  --append-output=FILE   append messages to FILE.
  -d,  --debug                print debug output.
  -q,  --quiet                quiet (no output).
  -v,  --verbose              be verbose (this is the default).
  -nv, --non-verbose          turn off verboseness, without being quiet.
  -i,  --input-file=FILE      download URLs found in FILE.
  -F,  --force-html           treat input file as HTML.
  -B,  --base=URL             prepends URL to relative links in -F -i file.

Download:
  -t,  --tries=NUMBER           set number of retries to NUMBER (0 unlimits).
       --retry-connrefused      retry even if connection is refused.
  -O   --output-document=FILE   write documents to FILE.
  -nc, --no-clobber             don't clobber existing files or use .# suffixes.
  -c,  --continue               resume getting a partially-downloaded file.
       --progress=TYPE          select progress gauge type.
  -N,  --timestamping           don't re-retrieve files unless newer than local.
  -S,  --server-response        print server response.
       --spider                 don't download anything.
  -T,  --timeout=SECONDS        set all timeout values to SECONDS.
       --dns-timeout=SECS       set the DNS lookup timeout to SECS.
       --connect-timeout=SECS   set the connect timeout to SECS.
       --read-timeout=SECS      set the read timeout to SECS.
  -w,  --wait=SECONDS           wait SECONDS between retrievals.
       --waitretry=SECONDS      wait 1...SECONDS between retries of a retrieval.
       --random-wait            wait from 0...2*WAIT secs between retrievals.
  -Y,  --proxy=on/off           turn proxy on or off.
  -Q,  --quota=NUMBER           set retrieval quota to NUMBER.
       --bind-address=ADDRESS   bind to ADDRESS (hostname or IP) on local host.
       --limit-rate=RATE        limit download rate to RATE.
       --dns-cache=off          disable caching DNS lookups.
       --restrict-file-names=OS restrict chars in file names to ones OS allows.

Directories:
  -nd, --no-directories            don't create directories.
  -x,  --force-directories         force creation of directories.
  -nH, --no-host-directories       don't create host directories.
  -P,  --directory-prefix=PREFIX   save files to PREFIX/...
       --cut-dirs=NUMBER           ignore NUMBER remote directory components.

HTTP options:
       --http-user=USER      set http user to USER.
       --http-passwd=PASS    set http password to PASS.
  -C,  --cache=on/off        (dis)allow server-cached data (normally allowed).
  -E,  --html-extension      save all text/html documents with .html extension.
       --ignore-length       ignore `Content-Length' header field.
       --header=STRING       insert STRING among the headers.
       --proxy-user=USER     set USER as proxy username.
       --proxy-passwd=PASS   set PASS as proxy password.
       --referer=URL         include `Referer: URL' header in HTTP request.
  -s,  --save-headers        save the HTTP headers to file.
  -U,  --user-agent=AGENT    identify as AGENT instead of Wget/VERSION.
       --no-http-keep-alive  disable HTTP keep-alive (persistent connections).
       --cookies=off         don't use cookies.
       --load-cookies=FILE   load cookies from FILE before session.
       --save-cookies=FILE   save cookies to FILE after session.
       --post-data=STRING    use the POST method; send STRING as the data.
       --post-file=FILE      use the POST method; send contents of FILE.

HTTPS (SSL) options:
       --sslcertfile=FILE     optional client certificate.
       --sslcertkey=KEYFILE   optional keyfile for this certificate.
       --egd-file=FILE        file name of the EGD socket.
       --sslcadir=DIR         dir where hash list of CA's are stored.
       --sslcafile=FILE       file with bundle of CA's
       --sslcerttype=0/1      Client-Cert type 0=PEM (default) / 1=ASN1 (DER)
       --sslcheckcert=0/1     Check the server cert agenst given CA
       --sslprotocol=0-3      choose SSL protocol; 0=automatic,
                              1=SSLv2 2=SSLv3 3=TLSv1

FTP options:
  -nr, --dont-remove-listing   don't remove `.listing' files.
  -g,  --glob=on/off           turn file name globbing on or off.
       --passive-ftp           use the \"passive\" transfer mode.
       --retr-symlinks         when recursing, get linked-to files (not dirs).

Recursive retrieval:
  -r,  --recursive          recursive download.
  -l,  --level=NUMBER       maximum recursion depth (inf or 0 for infinite).
       --delete-after       delete files locally after downloading them.
  -k,  --convert-links      convert non-relative links to relative.
  -K,  --backup-converted   before converting file X, back up as X.orig.
  -m,  --mirror             shortcut option equivalent to -r -N -l inf -nr.
  -p,  --page-requisites    get all images, etc. needed to display HTML page.
       --strict-comments    turn on strict (SGML) handling of HTML comments.

Recursive accept/reject:
  -A,  --accept=LIST                comma-separated list of accepted extensions.
  -R,  --reject=LIST                comma-separated list of rejected extensions.
  -D,  --domains=LIST               comma-separated list of accepted domains.
       --exclude-domains=LIST       comma-separated list of rejected domains.
       --follow-ftp                 follow FTP links from HTML documents.
       --follow-tags=LIST           comma-separated list of followed HTML tags.
  -G,  --ignore-tags=LIST           comma-separated list of ignored HTML tags.
  -H,  --span-hosts                 go to foreign hosts when recursive.
  -L,  --relative"; 
	}else if (strstr($paramaters, '-V')){
		$output = "GNU Wget 1.9.1

Copyright (C) 2003 Free Software Foundation, Inc.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

Originally written by Hrvoje Niksic <hniksic@xemacs.org>.";

	}else if (strstr($paramaters, 'http://')){
		if (ereg("http:\/\/.*\/.*\/",$paramaters)){
                    //file is inside of a folder
                    //echo "file is in a folder<br>";//go debug code
                    $pattern = "/http:\/\/([a-zA-Z\.-]*)(\/.*)\/(.*)/";
                    preg_match($pattern, $paramaters, $matches);
                    $domain =  sanitize($matches[1]);
                    $folder = sanitize($matches[2]);
                    $file =  sanitize($matches[3]);
                } else {
                	//echo "file is not in a folder<br>";//go debug code
                    $pattern = "/http:\/\/(.*)\/(.*)/";
                    preg_match($pattern, $paramaters, $matches);
                    $domain =  sanitize($matches[1]);
                    $folder = "";
                    $file =  sanitize($matches[2]);
	  	}
		//downloads a file and sends it with our xmlrpc
		downloadHTTPfile($domain, 80, $folder . "/" . $file);
		
	  	$ip = gethostbyname($domain);

	  	$url =  "http://" . $domain . $folder . "/" . $file;
	  	$cleanSysURL = sanitize_system_string($url, 5, 256);
  		
		if ($file == '')
		{
			$file = "index.html";
		}
		
		$output = "--$time--  " . $url . "\n=> `" . $file . "'\nResolving " .$domain . "... " . $ip ."\nConnecting to " .$domain . "[" . $ip ."]:80... connected.\nHTTP request sent, awaiting response... 200 OK\nLength: $size [text/html]\n\n100%[====================================>] $size         --.--K/s\n\n14:36:50 ($speed K/s) - `" . $file . "' saved [$size/$size]";
	}else if (strstr($paramaters, 'ftp://') && strstr($paramaters, '@') ){
		$pattern = "/ftp:\/\/(.*):(.*)@/i";
	  	preg_match($pattern, $paramaters, $matches);
		$username = sanitize($matches[1]);
		$password = sanitize($matches[2]);
  		$domain =  sanitize($matches[3]);
	  	$ip = gethostbyname($domain);
		$folder = sanitize($matches[4]);
  		$file =  sanitize($matches[5]);
		$url = 'ftp://'.$username.':'.$password.'@'.$domain.$folder.'/'.$file;
		$output = "--$time--  ".$url."\n           => `".$file."'\nResolving ".$domain."... ".$ip."\nConnecting to ".$domain."[".$ip."]:21... connected.\nLogging in as ".$username." ... Logged in!\n==> SYST ... done.    ==> PWD ... done.\n==> TYPE I ... done.  ==> CWD ".$folder." ... done.\n==> PASV ... done.    ==> RETR ".$file." ... done.\nLength: 729,821 (unauthoritative)\n\n100%[====================================>] 729,821      155.12K/s    ETA 00:00\n\n15:44:07 (146.77 KB/s) - `".$file."' saved [729821]";
	}else if (strstr($paramaters, 'ftp://')){
		if (ereg("ftp:\/\/.*\/.*\/",$paramaters)) {
			$pattern = "/ftp:\/\/([a-zA-Z\.-]*)(\/.*)\/(.*)/";
			preg_match($pattern, $paramaters, $matches);
			$domain =  sanitize($matches[1]);
			$folder = sanitize($matches[2]);
  			$file =  sanitize($matches[3]);
		} else {
			$pattern = "/(ftp|http):\/\/([a-zA-Z\.-]*)\/(.*)/";
			preg_match($pattern, $paramaters, $matches);
			$domain =  sanitize($matches[2]);
			$folder = "/";
  			$file =  sanitize($matches[3]);
		}
        if (strcmp($folder,"") == 0)
        {
            $folder = "/";
        }
        if (strcmp($file, ""))
            $file = ".listing";
		$username = "anonymous";
  		$ip = gethostbyname($domain);

		$url = 'ftp://'.$domain.$folder.'/'.$file;
		$output = "--$time--  ".$url."\n           => `".$file."'\nResolving ".$domain."... ".$ip."\nConnecting to ".$domain."[".$ip."]:21... connected.\nLogging in as ".$username." ... Logged in!\n==> SYST ... done.    ==> PWD ... done.\n==> TYPE I ... done.  ==> CWD ".$folder." ... done.\n==> PASV ... done.    ==> RETR ".$file." ... done.\nLength: 729,821 (unauthoritative)\n\n100%[====================================>] 729,821      155.12K/s    ETA 00:00\n\n15:44:07 (146.77 KB/s) - `".$file."' saved [729821]";
	}else if (preg_match("/\.[a-zA-Z]{2,4}\/.*/",$paramaters)) {
		$params = explode(" ", $paramaters);
		foreach ($params as $parma)
		{
			if (preg_match("/\.[a-zA-Z]{2,4}\/.*/",$parma))
				$url = "http://" .$parma;
		}
		$urlstuff = parse_url($url);
		
		$domain =  $urlstuff['host'];
		
		$lastSlash =  strrpos($urlstuff['path'], '/');
		$folder = substr($urlstuff['path'], 0, $lastSlash);
		$file =  substr($urlstuff['path'], $lastSlash+1, strlen($urlstuff['path'])-1);
		
		
		downloadHTTPfile($domain, 80, $folder . "/" . $file);
	  	$ip = gethostbyname($domain);

	  	$url =  "http://" . $domain . $folder . "/" . $file;
	  	$cleanSysURL = sanitize_system_string($url, 5, 256);
  		
		if ($file == '')
		{
			$file = "index.html";
		}
		$output = "--$time--  " . $url . "\n=> `" . $file . "'\nResolving " .$domain . "... " . $ip ."\nConnecting to " .$domain . "[" . $ip ."]:80... connected.\nHTTP request sent, awaiting response... 200 OK\nLength: $size [text/html]\n\n100%[====================================>] $size         --.--K/s\n\n14:36:50 ($speed K/s) - `" . $file . "' saved [$size/$size]";
		
	}else{//either I got a weird/bad url or they didn't give me http/ftp
		$output = "wget: missing URL\nUsage: wget [OPTION]... [URL]...\n\nTry `wget --help' for more options.";
	}

	return $output;
}


////////////////////////////////////////////////////////
//End Custom Honeypot Section
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Begin Logging Section
////////////////////////////////////////////////////////

writeLog($Owner, $HoneypotName, $DateTime, $Attack, $Signature, $LogType, $Filename, $DBName, $DBUser, $DBPass, $Server);

////////////////////////////////////////////////////////
//End Logging Section
////////////////////////////////////////////////////////
//End of template.php
////////////////////////////////////////////////////////

?>