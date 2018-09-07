<?php

namespace app\components;

use yii\base\Component;
use yii\helpers\FileHelper;
use yii\web\HttpException;

class UHttp extends Component {

    /**
     * Send a file with the 'Inline' method.
     * @param string $path Full path to the file that's being sent.
     * @param bool $removeAfterSend True to delete $path after it has been successfully sent, otherwise, false.
     * @param null|int $expiration Time in seconds for which the cache will be valid. Null for no cache.
     * @param bool $throw True to throw an exception when the file to be sent ($path) doesn't exist, otherwise, false.
     * @param \Exception $throwEx Exception to be thrown. If null, a generic HTTP 404 error will be thrown.
     * @return bool True if the file was successfully sent, otherwise, false.
     * @throws HttpException HTTP 404 error when $path is not found and $throw is set to true.
     * @throws \Exception Value of $throwEx (if not null) when $throw is set to true.
     */
    public static function sendInlineFile($path, $removeAfterSend = false, $expiration = null, $throw = false, $throwEx = null) {
        return UHttp::sendFile($path, 'inline', null, $removeAfterSend, $expiration, $throw, $throwEx);
    }

    /**
     * Send a file with the 'Attachment' method.
     * @param string $path Full path to the file that's being sent.
     * @param string $fileName A string that represents the file name. Pass null or an empty string to auto-generate one.
     * @param bool $removeAfterSend True to delete $path after it has been successfully sent, otherwise, false.
     * @param null|int $expiration Time in seconds for which the cache will be valid. Null for no cache.
     * @param bool $throw True to throw an exception when the file to be sent ($path) doesn't exist, otherwise, false.
     * @param \Exception $throwEx Exception to be thrown. If null, a generic HTTP 404 error will be thrown.
     * @return bool True if the file was successfully sent, otherwise, false.
     * @throws HttpException HTTP 404 error when $path is not found and $throw is set to true.
     * @throws \Exception Value of $throwEx (if not null) when $throw is set to true.
     */
    public static function sendAttachmentFile($path, $fileName = null, $removeAfterSend = false, $expiration = null, $throw = false, $throwEx = null) {
        return UHttp::sendFile($path, 'attachment', $fileName, $removeAfterSend, $expiration, $throw, $throwEx);
    }

    /**
     * Send a file with the 'Attachment' method.
     * @param string $content CSV text to send.
     * @param null|int $expiration Time in seconds for which the cache will be valid. Null for no cache.
     * @return bool True if the file was successfully sent, otherwise, false.
     */
    public static function sendAttachmentCsv($content, $expiration = null) {
        return UHttp::sendContent($content, 'export.csv', 'text/csv', 'attachment', $expiration);
    }

    /**
     * Send a file with the 'Attachment' method.
     * @param string $content XLSX binary string.
     * @param string $fileName File name to be sent via HTTP header. XLSX extension is added automatically if not present.
     * @param null|int $expiration Time in seconds for which the cache will be valid. Null for no cache.
     * @return bool True if the file was successfully sent, otherwise, false.
     */
    public static function sendAttachmentXlsx($content, $fileName = 'export', $expiration = null) {
        if (!UString::endsWith(UString::lowerCase($fileName), '.xlsx')) $fileName .= '.xlsx';
        return UHttp::sendContent($content, $fileName, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'attachment', $expiration);
    }

    /**
     * Send a file with the $contentDisposition method.
     * @param string $path Full path to the file that's being sent.
     * @param string $contentDisposition Content-Disposition header value.
     * @param string $fileName A string that represents the file name. Pass null or an empty string to auto-generate one.
     * @param bool $removeAfterSend True to delete $path after it has been successfully sent, otherwise, false.
     * @param null|int $expiration Time in seconds for which the cache will be valid. Null for no cache.
     * @param bool $throw True to throw an exception when the file to be sent ($path) doesn't exist, otherwise, false.
     * @param \Exception $throwEx Exception to be thrown. If null, a generic HTTP 404 error will be thrown.
     * @return bool True if the file was successfully sent, otherwise, false.
     * @throws HttpException HTTP 404 error when $path is not found and $throw is set to true.
     * @throws \Exception Value of $throwEx (if not null) when $throw is set to true.
     */
    private static function sendFile($path, $contentDisposition, $fileName = null, $removeAfterSend = false, $expiration = null, $throw = false, $throwEx = null) {
        if (!file_exists($path) && !is_file($path)) {
            if ($throw) {
                if ($throwEx !== null) {
                    throw $throwEx;
                }
                throw new HttpException(404);
            }
            return false;
        }

        if (!UString::isNullOrEmpty($fileName)) {
            $fileMime = FileHelper::getMimeTypeByExtension($fileName);
        } else {
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileMime = finfo_file($fileInfo, $path);
            finfo_close($fileInfo);
        }
        header('Content-Type: ' . $fileMime);

        //Use Content-Disposition: attachment to specify the filename
        if (UString::isNullOrEmpty($fileName)) $fileName = basename($path);
        header('Content-Disposition: ' . $contentDisposition . '; filename="' . $fileName . '"');

        if ($expiration !== null) {
            //Cache
            header('Cache-Control: public, max-age=' . $expiration); // max-age is in seconds
        } else {
            //No cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
        }
        //Cache common
        header('Pragma: public');

        //Define file size
        header('Content-Length: ' . filesize($path));

        ob_clean();
        flush();
        readfile($path);

        if ($removeAfterSend) {
            unlink($path);
        }

        return true;
    }

    /**
     * Send a file with the $contentDisposition method.
     * @param string $content Content that's being sent.
     * @param string $fileName File name to be sent via HTTP header.
     * @param string $mimeType MIME type of $content.
     * @param string $contentDisposition Content-Disposition header value.
     * @param null|int $expiration Time in seconds for which the cache will be valid. Null for no cache.
     * @return bool True if the file was successfully sent, otherwise, false.
     */
    private static function sendContent($content, $fileName, $mimeType, $contentDisposition, $expiration = null)
    {
        header('Content-Type: ' . $mimeType);

        //Use Content-Disposition: attachment to specify the filename
        header('Content-Disposition: ' . $contentDisposition . '; filename="' . $fileName . '"');

        if ($expiration !== null) {
            //Cache
            header('Cache-Control: public, max-age=' . $expiration); // max-age is in seconds
        } else {
            //No cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
        }
        //Cache common
        header('Pragma: public');

        //Define file size
        header('Content-Length: ' . strlen($content));

        ob_clean();
        flush();
        echo $content;

        return true;
    }
}
