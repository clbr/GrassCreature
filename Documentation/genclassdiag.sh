#!/bin/sh

lines=0
funcs=0

cat << "EOF"
graph g {
node [
shape = "plaintext"
fontsize = 10
fontname = "Helvetica"
]
graph [
overlap = false
]
EOF

num=0

for i in `find -name "*.php" | grep -v pchart`; do

	conts=`cat $i`

	l=`echo "$conts" | wc -l`
	lines=$((lines + l))

	conts=${conts/<script*<\/script>/}

	echo "$conts" | grep -q -e '^[[:space:]]*function' || continue

	echo -n "a$num "
	num=$((num+1))

cat << "EOF"
[label=<
<table bgcolor="palegoldenrod" border="0" cellborder="0" cellspacing="0" cellpadding="4">
<tr><td align="center" bgcolor="olivedrab4">
<font color="white" face="Helvetica" point-size="10">
EOF

	echo "${i#./}</font></td></tr>"
IFS="
"
	for funcname in `echo "$conts" | grep -e '^[[:space:]]*function' | \
		sed -e 's@^[[:space:]]*function @+@' \
		-e 's@[[:space:]]*{$@@' | perl -p -e 's@\n@\\n@' | sort`; do

		echo "<tr><td align=\"left\">$funcname</td></tr>"
	done

	echo "</table>>]"


	f=`echo "$conts" | grep -e '^[[:space:]]*function' | wc -l`
	funcs=$((funcs + f))
done

cur=0;
while [ $((cur < num)) -eq 1 ]; do
	[ $((cur % 6)) -eq 0 ] && echo -n "{ rank=same; "
	echo -n "a$cur "
	set=not
	[ $((cur % 6)) -eq 5 ] && echo " }" && set=set
	cur=$((cur+1))
done

[ "$set" = "set" ] || echo " }"

echo "{ rank=same; b c }"

#cur=0;
#while [ $((cur < num)) -eq 1 ]; do
#	echo "a$cur -> a$((cur+6))"
#	cur=$((cur+6))
#done

echo "b [label=\"$lines lines in total (including blanks and comments)\" \
	shape=note fillcolor=\"#ccff00\" style=filled]"
echo "c [label=\"$funcs functions\" shape=note fillcolor=\"#ccff00\" \
	style=filled]"
echo "b -- c [style=invis]"

echo "}"
