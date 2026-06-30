{{-- app/View/Components/ProductStars.php --}}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductStars extends Component
{
    public $rating;
    public $size;
    public $count;

    public function __construct($rating = 4.5, $size = 'md', $count = null)
    {
        $this->rating = $rating;
        $this->size = $size;
        $this->count = $count;
    }

    public function render(): View|Closure|string
    {
        return view('components.product-stars');
    }
}


{{-- app/View/Components/ProductPrice.php --}}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductPrice extends Component
{
    public $product;
    public $size;

    public function __construct($product, $size = 'lg')
    {
        $this->product = $product;
        $this->size = $size;
    }

    public function render(): View|Closure|string
    {
        return view('components.product-price');
    }
}


{{-- app/View/Components/ProductImageGallery.php --}}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductImageGallery extends Component
{
    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render(): View|Closure|string
    {
        return view('components.product-image-gallery');
    }
}


{{-- app/View/Components/ProductDetailsPanel.php --}}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductDetailsPanel extends Component
{
    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render(): View|Closure|string
    {
        return view('components.product-details-panel');
    }
}


{{-- app/View/Components/ProductTabs.php --}}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductTabs extends Component
{
    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render(): View|Closure|string
    {
        return view('components.product-tabs');
    }
}


{{-- app/View/Components/ProductRelated.php --}}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductRelated extends Component
{
    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render(): View|Closure|string
    {
        return view('components.product-related');
    }
}
