# INSTALLATION
```composer require dawid-bednarz/sf-context-bundle```
####1. Create entities file
```php
namespace App\Entity;

use DawBed\ContextBundle\Entity\AbstractContext as Base;

class Context extends Base
{
}
```
#### 2. Create context_bundle.yaml in your ~/config/packages directory
```yaml
dawbed_context_bundle:
   entities:
      DawBed\ContextBundle\Entity\AbstractContext: App\Entity\Context
```
# CONFIGURATION
#### Add your Context types (required)
```yaml
dawbed_context_bundle:
   types:
       registration: 1
```
