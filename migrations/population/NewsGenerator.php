<?php
declare(strict_types=1);

return function (int $count): iterable {
    foreach (getArticles($count) as $article) {
        $date = date('Y-m-d H:i:s', time() - rand(0, 86400 * 180));
        yield array_merge(
            $article,
            [
                'ownerId'   => rand(0, 10) > 5 ? 100 : 101,
                'createdAt' => $date,
                'updatedAt' => $date,
            ]
        );
    }
};

function getArticles(int $count): iterable
{
    while ($count-- > 0) {
        $content = file_get_contents(
            'https://yandex.ru/referats/write/?t=geology+literature+marketing+polit+physics+philosophy'
        );
        preg_match('~<strong>Тема: «(.*?)»</strong>~', $content, $m);
        $title = html_entity_decode($m[1]);
        preg_match('~<p>(.*)</p>~', $content, $m);
        $content = html_entity_decode('<p>'.$m[1].'</p>');
        yield ['title' => $title, 'content' => $content];
    }
}
