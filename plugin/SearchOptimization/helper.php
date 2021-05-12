<?php

// Содержимое SiteMap конфига по-умолчанию
const DEFAULT_SITEMAP = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ site_address }}</loc>
        <lastmod>{{ df('now', 'Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.95</priority>
    </url>
    {% for page in pages %}
        <url>
            <loc>{{ site_address }}/{{ page.address }}</loc>
            <lastmod>{{ page.date|df('Y-m-d') }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.55</priority>
        </url>
    {% endfor %}
    <url>
        <loc>{{ site_address }}/guestbook</loc>
        <lastmod>{{ df('now', 'Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    {% for category in publicationCategories %}
        <url>
            <loc>{{ site_address }}/{{ category.address }}</loc>
            <lastmod>{{ category.date|df('Y-m-d') }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
        </url>
    {% endfor %}
    {% for publication in publications %}
        <url>
            <loc>{{ site_address }}/{{ publication.address }}</loc>
            <lastmod>{{ publication.date|df('Y-m-d') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    {% endfor %}
    <url>
        <loc>{{ site_address }}/{{ catalog_address }}</loc>
        <lastmod>{{ df('now', 'Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.75</priority>
    </url>
    {% for category in catalogCategories %}
        <url>
            <loc>{{ site_address }}/{{ catalog_address }}/{{ category.address }}</loc>
            <lastmod>{{ category.date|df('Y-m-d') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    {% endfor %}
    {% for product in catalogProducts %}
        <url>
            <loc>{{ site_address }}/{{ catalog_address }}/{{ product.address }}</loc>
            <lastmod>{{ product.date|df('Y-m-d') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.85</priority>
        </url>
    {% endfor %}
</urlset>
EOD;

// Содержимое GMF конфига по-умолчанию
const DEFAULT_GMF = <<<EOD
<?xml version="1.0"?>
<rss xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>{{ shop_title }}</title>
        <link>{{ site_address }}</link>
        <description>{{ shop_description }}</description>
        {% for product in products %}
            <item>
                <g:id>{{ product.id }}</g:id>
                <g:title>{{ product.title }}</g:title>
                <g:description>{{ product.description|striptags }}</g:description>
                <g:link>{{ catalog_address }}/{{ product.address }}</g:link>
                <g:image_link>{{ product.getFiles().first().getPublicPath('middle') }}</g:image_link>
                <g:condition>new</g:condition>
                <g:availability>{{ product.stock > 0 ? 'in stock' : 'out of stock' }}</g:availability>
                <g:price>{{ product.price }} {{ currency }}</g:price>
                <g:google_product_category>{{ categories.firstWhere('uuid', product.category).title }}</g:google_product_category>
                <g:brand>{{ product.manufacturer }}</g:brand>
                <g:gtin>{{ product.barcode ? product.barcode : '' }}</g:gtin>
            </item>
        {% endfor %}
    </channel>
</rss>
EOD;

// Содержимое YML конфига по-умолчанию
const DEFAULT_YML = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<yml_catalog date="{{ df('now', 'Y-m-d H:m') }}">
    <shop>
        <name>{{ shop_title }}</name>
        <company>{{ company_title }}</company>
        <email>{{ email }}</email>
        <url>{{ site_address }}</url>
        <currencies>
            <currency id="{{ currency }}" rate="1"/>
        </currencies>
        <categories>
            {% for category in categories %}
                <category id="{{ category.id }}" parentId="{{ category.parent }}">{{ category.title }}</category>
            {% endfor %}
        </categories>
        <delivery-options>
            <option cost="{{ delivery_cost }}" days="{{ delivery_days }}"/>
        </delivery-options>
        <offers>
            {% for product in products %}
                <offer id="{{ product.buf }}">
                    <url>{{ catalog_address }}/{{ product.address }}</url>
                    {% for file in product.getFiles() %}
                        <picture>{{ file.getPublicPath('middle') }}</picture>
                    {% endfor %}
                    <name>{{ product.title }}</name>
                    <description>{{ product.description|striptags }}</description>
                    <categoryId>{{ categories.firstWhere('uuid', product.category).id }}</categoryId>
                    <price>{{ product.price }}</price>
                    <currencyId>{{ currency }}</currencyId>
                    <vendor>{{ product.manufacturer }}</vendor>
                    <vendorCode>{{ product.vendorcode }}</vendorCode>
                    <barcode>{{ product.barcode ? product.barcode : '' }}</barcode>
                    <country_of_origin>{{ product.country }}</country_of_origin>
                    <weight>{{ product.volume }}</weight>
                    <sales_notes></sales_notes>
                </offer>
            {% endfor %}
        </offers>
    </shop>
</yml_catalog>
EOD;

// Содержимое robots.txt по-умолчанию
const DEFAULT_ROBOTS = <<<EOD
User-agent: *
Allow: /
Allow: /uploads/
Disallow: /cup/
Sitemap: {{ site_address }}/sitemap.xml
EOD;
