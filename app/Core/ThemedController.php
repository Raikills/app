<?php
/**
 * Controller - base controller
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

namespace App\Core;

use Nova\Http\Response;
use Nova\Support\Contracts\RenderableInterface as Renderable;
use Nova\Support\Facades\Config;
use Nova\Support\Facades\View as ViewFactory;
use Nova\View\Layout;
use Nova\View\View;

use App\Core\BaseController;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use BadMethodCallException;


abstract class ThemedController extends BaseController
{
    /**
     * The currently used Layout.
     *
     * @var mixed
     */
    protected $layout;
    
    /**
     * The currently used Template.
     *
     * @var string
     */
    protected $theme = null;

    /**
     * The currently used Layout.
     *
     * @var string
     */
    protected $layout = 'Default';


    /**
     * Create a new ThemedController instance.
     */
    public function __construct()
    {
        // Setup the used Template to default, if it is not already defined.
        if (! isset($this->theme)) {
            $this->theme = Config::get('app.theme');
        }
    }

    /**
     * Create from the given result a Response instance and send it.
     *
     * @param mixed  $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function processResponse($response)
    {
        if ($response instanceof Renderable) {
            // If the response which is returned from the called Action is a Renderable instance,
            // we will assume we want to render it using the Controller's templated environment.

            if ((! $response instanceof Layout) && is_string($this->layout) && ! empty($this->layout)) {
                $response = ViewFactory::makeLayout($this->layout, $this->theme)->with('content', $response);
            }

            // Create a proper Response instance.
            $response = new Response($response->render(), 200, array('Content-Type' => 'text/html'));
        }

        // If the response is not a instance of Symfony Response, create a proper one.
        else if (! $response instanceof SymfonyResponse) {
            $response = new Response($response);
        }

        return $response;
    }

    /**
     * Return a default View instance.
     *
     * @return \Nova\View\View
     * @throws \BadMethodCallException
     */
    protected function getView(array $data = array())
    {
        // Get the currently called method.
        $method = $this->getMethod();

         // Transform the complete class name on a path like variable.
        $path = str_replace('\\', '/', static::class);

        // Check for a valid controller on App or Modules.
        if (preg_match('#^(.+)/Http/Controllers/(.*)$#i', $path, $matches)) {
            $view = $matches[2] .'/' .ucfirst($method);

            if ($matches[1] == 'App') {
               $module = null;
            } else  if (count($segments = explode('/', $matches[1])) === 2) {
               $module = last($segments);
            } else {
                throw new BadMethodCallException('Invalid Controller namespace: ' .static::class);
            }

            return ViewFactory::make($view, $data, $module, $this->theme);
        }

        // If we arrived there, the called class is not a Controller; go Exception.
        throw new BadMethodCallException('Invalid Controller namespace: ' .static::class);
    }

    /**
     * Return the current Template name.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Return a Layout instance.
     *
     * @return \Nova\View\Layout
     *
     * @throws \BadMethodCallException
     */
    public function getLayout()
    {
        if ($this->layout instanceof View) {
            return $this->layout;
        } else if (is_string($this->layout) && ! empty($this->layout)) {
            return ViewFactory::makeLayout($this->layout, $this->theme);
        }

        throw new BadMethodCallException('Method not available for the current Layout');
    }

    /**
     * Return the current Layout (class) name.
     *
     * @return string
     *
     * @throws \BadMethodCallException
     */
    public function getLayoutName()
    {
        if ($this->layout instanceof View) {
            return $this->layout->getName();
        } else if (is_string($this->layout)) {
            return $this->layout;
        }

        throw new BadMethodCallException('Method not available for the current Layout');
    }

}
