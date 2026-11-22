<?php
function eo($address,$linktext) {
    $address = strtolower($address);
    $coded = "";
    $unmixedkey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.@";
    $inprogresskey = $unmixedkey;
    $mixedkey = "";
    $unshuffled = strlen($unmixedkey);
    for ($i = 0; $i <= strlen($unmixedkey); $i++) {
		$ranpos = rand(0,$unshuffled-1);
		$nextchar = $inprogresskey{$ranpos};
		$mixedkey .= $nextchar;
		$before = substr($inprogresskey,0,$ranpos);
		$after = substr($inprogresskey,$ranpos+1,$unshuffled-($ranpos+1));
		$inprogresskey = $before.''.$after;
		$unshuffled -= 1;
    }
    $cipher = $mixedkey;

    $shift = strlen($address);

    $txt = "<script type=\"text/javascript\">\n".
	"<!--\n";

    for ($j=0; $j<strlen($address); $j++) {
		if (strpos($cipher,$address{$j}) == -1 )
		{
			$chr = $address{$j};
			$coded .= $address{$j};
		}
		else
		{
			$chr = (strpos($cipher,$address{$j}) + $shift) % strlen($cipher);
			$coded .= $cipher{$chr};
		}
    }


    $txt .= "	coded = \"".$coded."\";\n".
	"	key = \"".$cipher."\";\n".
	"	shift = coded.length;\n".
	"	link = \"\";\n".
	"	linktext = \"".$linktext."\";\n".
	"	for (i=0; i<coded.length; i++) {\n" .
	"		if (key.indexOf(coded.charAt(i)) == -1) {\n".
	"			ltr = coded.charAt(i);\n".
	"			link += (ltr);\n".
	"		}\n".
	"		else {\n".
	"			ltr = (key.indexOf(coded.charAt(i))-shift+key.length) % key.length;\n".
	"			link += (key.charAt(ltr));\n".
	"		}\n".
	"	}\n".
	"	if (linktext == \"\") {\n".
	"		document.write(\"<a href='mailto:\"+link+\"'>\"+link+\"</a>\");\n".
	"	}\n" .
	"	else {\n".
	"		document.write(\"<a href='mailto:\"+link+\"'>\"+linktext+\"</a>\");\n".
	"	}\n".
	"-->\n".
	"</script>\n";
    return $txt;
}
?>
