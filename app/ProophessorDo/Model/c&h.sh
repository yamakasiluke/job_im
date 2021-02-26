for i in *;
do
    src=$i
    command="$(basename "$i" .php)Command"
    handler="$(basename "$i" .php)Handler"
    cp -nv $src "../Command/${command}.php";
    cp -nv $src "../Handler/${handler}.php";
done
