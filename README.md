# Usage
Use Laravel web route to generate sitemap structure, use for parent/child relation and backend left side menu for AdminLte

# Install
    composer require virtualorz/sitemap
    
#Config
edit config/app.php
    
    'providers' => [
        ...
        Virtualorz\Sitemap\SitemapServiceProvider::class
    ]
    
    'aliases' => [
        ...
        'Sitemap' => Virtualorz\Sitemap\Facades\Sitemap::class,
    ]
    
#Method

######getTreeView
`return tree struce Array`

######getNaviPath
`return navipath html`

######getParents
`return parent node route item`

######getMenu
`return left side menu structure Array`

#Route Example
    Route::get('/customer',
            [
                'as' => 'backend.customer.index' ,// user for id in sitemap
                'uses' => 'backend\CustomerController@index', //controller name
                'parent' => 'backend.index', //parent sitemap id
                'name' => 'Customer List', //customer name for this item
                'label' =>'Data Manage', //backend menu label text for this item
                'fa' => 'fa-database' //backend menu fa icon for this item
            ]);

