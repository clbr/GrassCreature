#!/bin/sh

for i in `find -name "*.php" | grep -v pchart`; do

	echo "$i:"
	grep -e '^[[:space:]]*function' $i
done
