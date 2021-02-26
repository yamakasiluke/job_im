for i in *;

do
    src=$i
    tgt=$(echo $i | sed -r 's/(^|_)([a-z])/\U\2/g')
    tgt="$(basename "$tgt" .php)"
#    printf  "${tgt%Event}.php"
    tgt="${tgt%Event}.php"
    mv -v $src $tgt
done
