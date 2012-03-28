#!/bin/sh

lines=0
funcs=0

for i in `find -name "*.php" | grep -v pchart`; do

	echo "$i:"
	grep -e '^[[:space:]]*function' $i

	f=`grep -e '^[[:space:]]*function' $i | wc -l`
	funcs=$((funcs + f))

	l=`wc -l $i | cut -d" " -f1`
	lines=$((lines + l))
done

echo "$lines lines in total (including blanks and comments)"
echo "$funcs functions"
