# INSTALLATION
```composer require dawid-bednarz/sf-context-bundle```
####1. Create entities file
```php
namespace App\Entity;

use DawBed\PHPContext\Context as Base;

class Context extends Base
{
}
```
#### 2. Create status_bundle.yaml in your ~/config/packages directory
```yaml
dawbed_context_bundle:
    entities:
      Context: 'App\Entity\Context'
```
# CONFIGURATION
#### Add your Context types (required)
```yaml
dawbed_context_bundle:
   types:
       registration: 1
```
