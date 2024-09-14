<?php
function redirectTo(string $path)
{
    // http_response_code(302);
    //http_response_code(Http::REDIRECT_STATUS_CODE);
    header("Location:{$path}"); //redirection with headers
    exit;
}
?>