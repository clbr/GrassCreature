#!/bin/sh

lines=0
funcs=0

cat << "EOF"
digraph g {
node [
shape = "box"
]
EOF

num=0

for i in `find -name "*.php" | grep -v pchart`; do

	grep -q -e '^[[:space:]]*function' $i || continue

	echo -n "a$num "
	num=$((num+1))

	echo -n "[label =\"${i#./}\n"
	grep -e '^[[:space:]]*function' $i | sed -e 's@^[[:space:]]*@@' \
		-e 's@[[:space:]]*{$@@' | perl -p -e 's@\n@\\n@'

	echo "\"]"


	f=`grep -e '^[[:space:]]*function' $i | wc -l`
	funcs=$((funcs + f))

	l=`wc -l $i | cut -d" " -f1`
	lines=$((lines + l))
done

echo "b [label=\"$lines lines in total (including blanks and comments)\"]"
echo "c [label=\"$funcs functions\"]"

echo "}"
