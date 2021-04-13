<?php

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;

if (!function_exists('clone_thumbnail')) {
    /**
     *
     * @param type $thumbnail
     * @param type $source
     */
    function clone_thumbnail($thumbnail, $source)
    {
        $new_image_url = config('app.default_thumb_url');

        if (!empty($thumbnail) && !empty($source)) {

            if (filter_var($thumbnail, FILTER_VALIDATE_URL) !== false) {
                return $thumbnail;
            }

            $source_file = storage_path("app/public/" . (str_replace('/storage/', '', $thumbnail)));
            if (file_exists($source_file)) {
                $ext = pathinfo(basename($thumbnail), PATHINFO_EXTENSION);
                $new_image_name = uniqid() . '.' . $ext;
                ob_start();

                $destination_file = str_replace("uploads_tmp", $source, str_replace(basename($thumbnail), $new_image_name, $source_file));
                if (strpos($destination_file, 'uploads') !== false) {
                    $destination_file = str_replace("uploads", $source, $destination_file);
                }

                \File::copy($source_file, $destination_file);
                ob_get_clean();
                $new_image_url = "/storage/" . $source . "/" . $new_image_name;
            }
        }

        return $new_image_url;
    }
}

if (!function_exists('custom_url')) {
    /**
     * Custom function to handle server proxy
     * @param $url
     * @return Application|UrlGenerator|string
     */
    function custom_url($url)
    {
        $proxy = config('constants.server_proxy');
        if (!$proxy) {
            return url($url);
        }
        return url($proxy . '/' . ltrim($url, '/')); // remove first slash from URL as it already appended
    }
}

if (!function_exists('invoke_starter_projects_command')) {
    /**
     * Invokes the starter projects command in background
     */
    function invoke_starter_projects_command()
    {
        dispatch(function () {
            \Artisan::call('project:starters');
        });
    }
}

if (!function_exists('get_user_id_by_token')) {
    /**
     * Get user id from BearerToken - can be used in commands and background jobs
     * In background jobs session data doesn't persist, this would be helpful
     * @param $token
     * @return false|mixed
     */
    function get_user_id_by_token($token)
    {
        $key_path = Passport::keyPath('oauth-public.key');
        $parseTokenKey = file_get_contents($key_path);
        $token = (new Parser())->parse((string)$token);
        $signer = new Sha256();
        if ($token->verify($signer, $parseTokenKey)) {
            return (int)$token->getClaim('sub');
        }
        return false;
    }
}

if (!function_exists('get_job_from_payload')) {
    /**
     * Get the job name from payload
     * @param $payload
     * @return string|null
     */
    function get_job_from_payload($payload)
    {
        $payload = json_decode($payload, true);
        return get_base_name($payload['displayName']);
    }
}

if (!function_exists('get_base_name')) {
    /**
     * Get the base class name of the job.
     *
     * @return string|null
     */
    function get_base_name($name): ?string
    {
        if (null === $name) {
            return null;
        }

        return Arr::last(explode('\\', $name));
    }
}

if (!function_exists('is_valid_date')) {
    /**
     * Validates the any format date string
     * @param $date
     * @return bool
     */
    function is_valid_date($date)
    {
        return (bool)strtotime($date);
    }
}

if (!function_exists('xAPIFormatDuration')) {
    /**
     * Format 'duration' value in seconds to hh:mm:ss format
     * e.g., PT24S to 0:24
     * 
     * @param string $duration
     * @param boolean $formatValue Return formatted value in hh:mm:ss format. Defaults to true
     * @return string
     */
    function xAPIFormatDuration($duration, $formatValue = true)
    {
        $rawDuration = str_replace(array('PT', 'S'), '', $duration);
        if ($formatValue) {
            $seconds = round($rawDuration);
            $formatted = sprintf('%02d:%02d', ($seconds / 60 % 60), $seconds % 60);
            if (($seconds / 3600) >= 1) {
                $formatted = sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
            }
            return $formatted;
        }
        return $rawDuration;
    }
}

if (!function_exists('recursive_array_search')) {
    function recursive_array_search($needle, $haystack, $currentKey = '') {
        foreach($haystack as $key=>$value) {
            if (is_array($value)) {
                $nextKey = recursive_array_search($needle,$value, $currentKey . '[' . $key . ']');
                if ($nextKey) {
                    return $nextKey;
                }
            }
            else if($value==$needle) {
                return is_numeric($key) ? $currentKey . '[' .$key . ']' : $currentKey . '["' .$key . '"]';
            }
        }
        return false;
    }
}

if (!function_exists('recursive_array_search_insert')) {
    function recursive_array_search_insert($value, &$node, $insert = '') {
        if (is_array($node)) {
            if (isset($node['relation-sub-content-id']) && $value === $node['relation-sub-content-id']) {
                if (!isset($node['answer'])) {
                    $node['answer'] = [];
                }
                $node['answer'][] = $insert;
            }
            foreach ($node as &$childNode) {
                recursive_array_search_insert($value, $childNode, $insert);
            }
        }
    }
}
