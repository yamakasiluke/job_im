for i in *;
do
    src=$i
    tgt=$(echo $i | sed -E 's/_(.)/\U\1/g')
    mv -v $src $tgt
    mv -v "$tgt" "${tgt}1";
    mv -v "${tgt}1" "${tgt^}";
done
