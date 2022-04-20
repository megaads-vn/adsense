<?php 

namespace Megaads\Adsense\Middlewares;


use Closure;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ResourceInjectionMiddleware 
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        $response = $next($request);
        $this->modifyResponse($request, $response);
        return $response;
    }

    /**
     * @param use Illuminate\Http\Request request
     * @param Symfony\Component\HttpFoundation\Response response
     * @return Symfony\Component\HttpFoundation\Response response
     */
    private function modifyResponse(Request $request, Response $response) {
        $acceptHeaders = [
            'text/html; charset=UTF-8',
            'text/html'
        ];
        $contentType = $response->headers->get('Content-Type');
        
        if (!empty($contentType) && !in_array($contentType, $acceptHeaders)) {
            return $response;
        } else if (!$request->ajax()) {
            $content = $response->getContent();
            $headPos = strripos($content, '</head>');
            $defineJsLocale = view('adsense::resource')->render();
            if (false !== $headPos) {
                $content = substr($content, 0, $headPos) . $defineJsLocale  . substr($content, $headPos);
            }
            
            // Update the new content and reset the content length
            $response->setContent($content);
            $response->headers->remove('Content-Length');
        }
        return $response;
    }

    /**
     * @param array files
     * @return string retval
     */
    private function getRendererJs($files = []) {
        $retval = '<script type="text/javascript"> var locales = \'' . json_encode(Config::get('localization::module.locales')) . '\';</script>';
        if (count($files) > 0) {
            foreach ($files as $file) {
                $retval .= '<script type="text/javascript" src="' . $file . '?v=' . config('sa.version') . '"></script>';
            }
        }
        return $retval;
    }

    /**
     * 
     * @param array files
     * @return string retval
     */
    private function getRendererCss($files = []) {
        $retval = '';
        if (count($files) > 0) {
            foreach ($files as $file) {
                $retval .= '<link rel="stylesheet" href="' . $file . '?v=' . config('sa.version') . '" >';
                // $retval .= '<link rel="preload" href="' . $file . '?v=' . config('sa.version') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"><noscript><link rel="stylesheet" href="' . $file . '?v=' . config('sa.version') . '" ></noscript>';
            }
        }
        return $retval;
    }

    /**
     * 
     * @param
     * @return string retval
     */
    private function getRendererListSelectLanguage() {
        $retval = '';
        $listLocales = Config::get('localization::module.locales');
        if (count($listLocales) > 0) {
            $html = '<ul class="list-multilang">';
            foreach ($listLocales as $key => $text) {
                $html .= '<li>
                    <a href="/" data-lang_key="' . $key . '" class="js-multilang-link multilang-link">
                        <span class="multilang-innertext"><strong>' . $text . '</strong></span>
                        <img src="/images/'. $key .'.png" class="multilang-icon"
                            alt="' . $text . '">
                    </a>
                </li>';
            }
            $html .= '</ul>';
            $retval = $html;
        }
        return $retval;
    }

    private function createElement($element, $type, $filePath) {
        $dom = new DOMDocument();
        $element = $dom->createElement($element);
        $element->setAttribute( 'type', $type );
        if ($type == 'js') {
            $element->setAttribute( 'src', $filePath);
        } else if ($type == 'css') {
            $element->setAttribute( 'href', $filePath);
        }
        return $element;
    }
}