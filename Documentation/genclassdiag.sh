#!/bin/sh

lines=0
funcs=0

cat << "EOF"
node [
shape = "box"
]
EOF

for i in `find -name "*.php" | grep -v pchart`; do

	echo "${i#./}:"
	grep -e '^[[:space:]]*function' $i | sed -e 's@^[[:space:]]*@@' \
		-e 's@[[:space:]]*{$@@'

	f=`grep -e '^[[:space:]]*function' $i | wc -l`
	funcs=$((funcs + f))

	l=`wc -l $i | cut -d" " -f1`
	lines=$((lines + l))
done

echo "$lines lines in total (including blanks and comments)"
echo "$funcs functions"
