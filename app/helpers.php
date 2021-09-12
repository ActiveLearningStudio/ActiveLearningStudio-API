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
        error_reporting(0);
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
        if ($payload) {
            $payload = json_decode($payload, true);
            return get_base_name($payload['displayName']);
        }
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
    function recursive_array_search($needle, $haystack, $currentKey = '')
    {
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
    function recursive_array_search_insert($value, &$node, $insert = '')
    {
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

if (!function_exists('getEducationalLevel')) {
    function getEducationalLevel($grade)
    {
        // Range: -1 to 13 
        // Note: Pre-K = -1, Kindergarten = 0, Adult = 13 
        $grades = [
            'Kindergarten-Grade 2 (Ages 5-7)' => [0, 2],
            'Grades 3-5 (Ages 8-10)' => [3, 5],
            'Grades 6-8 (Ages 11-13)' => [6, 8],
            'Grades 9-10 (Ages 14-16)' => [9, 10],
            'Grades 11-12 (Ages 16-18)' => [11, 12],
            'College & Beyond' => [13],
            'Professional Development' => [13],
            'Special Education' => [13]
        ];
        return array_key_exists($grade, $grades) ? $grades[$grade] : [0, 2]; 
    }
}

if (!function_exists('getFrontURL')) {
    function getFrontURL()
    {
        $front_url = config('constants.front-url');
        if (strpos($front_url,'://') === false) {
            // If not an absolute path, then get the origin.
            $front_url = request()->headers->get('origin');
            if (!$front_url) {
                // If nothing works, take the http host
                $front_url = request()->getSchemeAndHttpHost();
            }
        }
        return $front_url;
    }
}

if (!function_exists('html_escape')) {
	/**
	 * Returns HTML escaped variable.
	 *
	 * @param	mixed	$var		The input string or array of strings to be escaped.
	 * @param	bool	$double_encode	$double_encode set to FALSE prevents escaping twice.
	 * @return	mixed			The escaped string or array of strings as a result.
	 */
	function html_escape($var, $double_encode = true)
	{
        if (empty($var)) {
            return $var;
        }

        if (is_array($var))	{
            foreach (array_keys($var) as $key) {
                $var[$key] = html_escape($var[$key], $double_encode);
            }

            return $var;
        }

        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8', $double_encode);
    }
}

if (!function_exists('formatDuration')) {
    /**
     * Format 'duration' value in seconds to hh:mm:ss format
     * e.g., PT24S to 0:24
     * 
     * @param string $duration
     * @return string
     */
    function formatDuration($duration)
    {
        $raw_duration = str_replace(array('PT', 'S'), '', $duration);
        $seconds = round($raw_duration);
     
        $formatted = sprintf('%02d:%02d', ($seconds / 60 % 60), $seconds % 60);
        if (($seconds / 3600) >= 1) {
            $formatted = sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
        }

        return $formatted;
    }
}
