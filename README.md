# PHPRest

This is tornado-like wrapper for creating HTTP RESTful services.

## Example

### Handlers declaration

```php
/**
 * Declare the URL handlers.
 * Handler must be an array: array("/url-pattern/", "\\Name\\Of\\Class")
 * first element is string (for string URL path match) or regexp
*/
$handlers = array(
    array("/ajax/register/", '\\AjaxRegisterHandler'),
    array("#^/product/([0-9]+)/reviews/$#", '\\ProductReviewHandler'),
    array("#^/Example.php/news/(.*)$#", '\\NewsHandler'),
);
```

### Implementation of handlers

```php
require_once "AjaxHandler.class.php";

/**
 * All handlers must be subclass of PHPRest\HttpHandler
 */
class AjaxRegisterHandler extends PHPRest\AjaxHandler
{
    /**
     * handler POST-requests
     */
    public function post() 
    {
        // Get POST params
        $login = $this->getBodyParam("login");
        $pass = $this->getBodyParam("pass");

        // Get URL-query params
        $redirect_url = $this->getQueryParam("next");

        // Check form fields
        if (!$login) $this->setError("login", "Required field");
        if (!$pass) $this->setError("pass", "Required field");

        // If errors found, show it
        if ($this->getErrors()) {
            $this->writeErrors();
            return;
        }
        // Do other stuff...

        $response = array("user_id" => 100);
        $this->write($response);
    }
}


class ProductReviewHandler extends PHPRest\AjaxHandler {};
class NewsHandler extends PHPRest\AjaxHandler {};
```

### Setting up HTTP router

```php
require_once "HttpRouter.class.php";

// Initialize HTTP router
$router = new PHPRest\HttpRouter($handlers);
// Initialize HTTP requests handler
$router->initHandler();
```

