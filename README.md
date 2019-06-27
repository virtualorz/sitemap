# Usage
Use Laravel web route to generate sitemap structure, use for parent/child relation and backend left side menu for AdminLte

# Install
    composer require virtualorz/sitemap
    
# Config
edit config/app.php
    
    'providers' => [
        ...
        Virtualorz\Sitemap\SitemapServiceProvider::class
    ]
    
    'aliases' => [
        ...
        'Sitemap' => Virtualorz\Sitemap\Facades\Sitemap::class,
    ]
    
# Method

###### getTreeView
    return an Array , the key is parent node name

###### getNaviPath($pageName = null)
    return navipath node array,
    if $pageName is null return the current page navi path,
    if not return the assign page's navi path

###### getParents($pageName = null)
    return parent node route item,
    if $pageName is nul return the current page parent node,
    if not return the assign page's parent node

###### getMenu($parmissionArray = null)
    return left side menu structure Array,
    if $parmissionArray is null return the full mene
    if not return the node in $parmissionArray

###### routStruct($parent,$sitemap)
    return an nested Array for root parent i $parent data from $sitemap,
    the $sitemap paremeter is result from getTreeVIew method

# Example for route/web.php
    Route::get('/customer',
            [
                'as' => 'backend.customer.index' ,// user for id in sitemap
                'uses' => 'backend\CustomerController@index', //controller name
                'parent' => 'backend.index', //parent sitemap id
                'name' => 'Customer List', //customer name for this item
                'label' =>'Data Manage', //backend menu label text for this item
                'fa' => 'fa-database' //backend menu fa icon for this item
            ]);

# Example for create tree view structure
    $sitemap = Sitemap::getTreeView();
    $sitemap = Sitemap::routStruct('root',$sitemap);

# 中文版本文件
[Sitemap : 使用Laravel Route產生網站結構陣列](http://www.alvinchen.club/2019/06/26/%e4%bd%9c%e5%93%81laravel-package-sitemap-%e4%bd%bf%e7%94%a8laravel-route%e7%94%a2%e7%94%9f%e7%b6%b2%e7%ab%99%e7%b5%90%e6%a7%8b%e9%99%a3%e5%88%97/)
