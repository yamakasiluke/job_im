for i in *;

do
    src=$i
    tgt=$(echo $i | sed -r 's/(^|_)([a-z])/\U\2/g')
    mv -v $src $tgt
done
