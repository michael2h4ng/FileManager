<?php namespace App\Services;

class MetaInfoService {

	/**
	 * A multibytes-safe version of the PHP implementation pathinfo()
	 *
	 * @param  string  $path
	 * @return array
	 */
	static public function mb_pathinfo($path, $options = null)
	{
        $ret = array('dirname' => '', 'basename' => '', 'extension' => '', 'filename' => '');
        $m = array();
        preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $path, $m);
        if (array_key_exists(1, $m)) {
            $ret['dirname'] = $m[1];
        }
        if (array_key_exists(2, $m)) {
            $ret['basename'] = $m[2];
        }
        if (array_key_exists(5, $m)) {
            $ret['extension'] = $m[5];
        }
        if (array_key_exists(3, $m)) {
            $ret['filename'] = $m[3];
        }
        switch ($options) {
            case PATHINFO_DIRNAME:
            case 'dirname':
                return $ret['dirname'];
                break;
            case PATHINFO_BASENAME:
            case 'basename':
                return $ret['basename'];
                break;
            case PATHINFO_EXTENSION:
            case 'extension':
                return $ret['extension'];
                break;
            case PATHINFO_FILENAME:
            case 'filename':
                return $ret['filename'];
                break;
            default:
                return $ret;
        }
	}

    /**
     * Return max file size configuration
     *
     * @param  bool  $bytes
     * @return string | int
     */
    static public function max_file_size($bytes = True)
    {
        if (! $bytes)
        {
            return ini_get('post_max_size');
        }

        $val = trim(ini_get('post_max_size'));
        $last = strtolower($val[strlen($val)-1]);

        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
            default:
                $val;
        }

        return $val;
    }

}
