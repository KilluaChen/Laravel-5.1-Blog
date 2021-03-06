<?php

namespace App\Models;

use App\Helper\Curl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $table = 'post';
    protected $primaryKey = 'post_id';
    protected $guarded = [];

    public static function getRandomRead()
    {
        $cache_key = 'rand_read';
        $data = \Cache::get($cache_key);
        if (!$data) {
            $query = Post::select(['post_id', 'title', 'read_count'])->where(['state_id' => 10])->limit(10);
            $data = $query->orderByRaw('RAND()')->get();
            \Cache::put($cache_key, $data, 0.1);
        }
        return $data;
    }

    public static function getHotTag()
    {
        $cache_key = 'hot_tag';
        $data = \Cache::get($cache_key);
        if (!$data) {
            $query = Tag::select(['post_count', 'tag_name'])->distinct()->join('post_tag', 'tag.tag_id', '=', 'post_tag.tag_id');
            $data = $query->orderBy('post_count', 'desc')->get()->toArray();
            foreach ($data as $key => &$item) {
                $i = ['label' => $item['tag_name'] . '(' . $item['post_count'] . ')', 'url' => '/tags/' . $item['tag_name'], 'target' => '_top'];
                $item = $i;
            }
        }
        return json_encode($data);
    }


    public static function updateCommentCount($posts)
    {
        $disqus_count_url = 'http://larry666.disqus.com/count-data.js?2=';
        foreach ($posts as $post) {
            $url = route('post.view', ['post_id' => $post->post_id]);
            $result = Curl::get($disqus_count_url . $url . '&rand=' . rand(1, 100));
            if (preg_match('/counts":\[(.*?)]}\);/is', $result, $m)) {
                $result = json_decode($m[1]);
                $post->comment_count = $result->comments;
                $post->save();
            }
        }
    }


    public static function getState($status_id = null)
    {
        $map = [10 => '启用', 20 => '停用', 30 => '草稿'];
        return self::_getMap($status_id, $map);
    }

    public static function getOriginalMap($status_id = null)
    {
        $map = [1 => '原创', 2 => '转载'];
        return self::_getMap($status_id, $map);
    }

    private static function _getMap($status_id, $map)
    {
        if (array_key_exists($status_id, $map)) {
            return $map[$status_id];
        }
        return $map;
    }

}
