<?php

  require_once 'functions.php';

  $EFUSION_SITE_ID = '3228953';
  $EFUSION_TOKEN = '';
  $EFUSION_USERNAME = "tech@websitesuccess.co.uk";
  $EFUSION_PASSWORD = "DevTime69!";

  $VEND_URL = '';
  $VEND_TOKEN = 'BQoSDDIat2f8bCOn1uQ3N_1QxqXygPWaq0Cnl591';

  // $method = str_replace('/', '', $_SERVER['PATH_INFO']);
  $parameters = $_REQUEST;

  // header('Content-Type: application/json');
  //
  // if (function_exists($method)) {
  //     echo call_user_func($method, $parameters);
  // } else {
  //     bulk_update();
  // }

  bulk_update();

  function bulk_update () {
    global $EFUSION_SITE_ID, $EFUSION_USERNAME, $EFUSION_PASSWORD;
    // ob_implicit_flush(true);
    // ob_end_flush();
    $vendProducts = [];
    $vendInventoriesData = [];

    $after = 0;
    do {
      $vendGetProducts = vendGet("https://theruncompany.vendhq.com/api/2.0/products?after=$after");
      $vendGetProductsData = $vendGetProducts->data;
      $after = $vendGetProducts->version->max;
      $vendGetProductsCount = count($vendGetProductsData);
      $vendProducts = array_merge($vendProducts, $vendGetProductsData);
    } while ($vendGetProductsCount > 0);

    $after = 0;
    do {
      $vendGetInventory = vendGet("https://theruncompany.vendhq.com/api/2.0/inventory?after=$after&page_size=500");
      $vendGetInventoryData = $vendGetInventory->data;
      $after = $vendGetInventory->version->max;
      $vendGetInventoryCount = count($vendGetInventoryData);
      $vendInventoriesData = array_merge($vendInventoriesData, $vendGetInventoryData);
    } while ($vendGetInventoryCount > 0);

    $vendInventories = [];
    foreach ($vendInventoriesData as $i) {
      $vendInventories[ $i->product_id ] = $i;
    }

    // $vendInventories = array_slice($vendInventories, 500);
    // $vendProducts = array_slice($vendProducts, 0, 100);

    $formattedProducts = [];
    foreach ($vendProducts as $vendProduct) {
      if (!array_key_exists($vendProduct->handle, $formattedProducts)) {
        // if product handle not recognised build initial product and information {$vendProduct->___}
        $createProduct = "
        <Products>
          <productId>0</productId>
          <productCode>{$vendProduct->handle}</productCode>
          <productName>{$vendProduct->name}</productName>
          <description><![CDATA[". htmlentities($vendProduct->description). "]]></description>
          <smallImage>{$vendProduct->image_thumbnail_url}</smallImage>
          <largeImage>{$vendProduct->image_url}</largeImage>
          <cataloguesArray/>
          <pricesSaleArray>
            <string>GB/{$vendProduct->price_including_tax}</string>
          </pricesSaleArray>
          <pricesRetailArray>
            <string>GB/{$vendProduct->price_including_tax}</string>
          </pricesRetailArray>
          <pricesWholesaleArray/>
          <wholesaleTaxCodeArray/>
          <taxCodeArray/>
          <groupProducts/>
          <groupProductsDescriptions/>
          <supplierCommission>0</supplierCommission>
          <weight>0</weight>
          <relatedProducts/>
          <tags/>
          <unitType/>
          <minUnits></minUnits>
          <maxUnits></maxUnits>
          <onOrder>0</onOrder>
          <inStock></inStock>
          <inventoryControl>false</inventoryControl>
          <canPreOrder>false</canPreOrder>
          <enabled>true</enabled>
          <deleted>false</deleted>
          <custom1/>
          <custom2/>
          <custom3/>
          <custom4/>
          <popletImages/>
          <captureDetails>false</captureDetails>
          <downloadLimitCount>0</downloadLimitCount>
          <limitDownloadsToIP>0</limitDownloadsToIP>
          <isOnSale>false</isOnSale>
          <hideIfNoStock>false</hideIfNoStock>
          <isGiftVoucher>false</isGiftVoucher>
          <enableDropShipping>false</enableDropShipping>
          <productHeight>0</productHeight>
          <productDepth>0</productDepth>
          <excludeFromSearch>false</excludeFromSearch>
          <productTitle>{$vendProduct->name}</productTitle>
          <cycletypeId>1</cycletypeId>
          <cycletypeCount>-1</cycletypeCount>
          <slug></slug>
          <roleResponsible/>
          <metaDescription>". strip_tags($vendProduct->description) . "</metaDescription>
        </Products>
        ";
        // var_dump($createProduct);
        $product = simplexml_load_string($createProduct);
        // var_dump($product->asXML());

        $product->cataloguesArray = null;

        foreach ($vendProduct->categories as $key => $category) {
          $product->cataloguesArray->addChild("string", $category->name);
        }

        $poplets = '';
        foreach ($vendProduct->images as $image) {
          $poplets .= ($image->sizes->standard . ";");
        }
        $product->popletImages = $poplets;

        if (!empty($vendProduct->variant_options)) {
          $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ] = [];
          $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ]['productid'] = $vendProduct->id;

          if (array_key_exists( $vendProduct->id, $vendInventories)) {
            $count = $vendInventories[$vendProduct->id]->inventory_level;
          } else {
            $count = 0;
          }

          $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ]['count'] = $count;

          foreach ($vendProduct->variant_options as $variantOption) {
            $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ]['variant_options'][ $variantOption->name ] = $variantOption->value;
          }
        } else {
          if (array_key_exists( $vendProduct->id, $vendInventories)) {
            $count = $vendInventories[$vendProduct->id]->inventory_level;
          } else {
            $count = 0;
          }
          $product->inStock = $count;
        }

        $formattedProducts[$vendProduct->handle]['xml'] = $product->asXML();


      } else {
        // product info already built so this is a variant
        $product = simplexml_load_string($formattedProducts[$vendProduct->handle]['xml']);

        if ( empty($product->popletImages) && !empty($vendProduct->images) ) {
          // in case the initial product wasn't the variant with images
          $poplets = '';
          foreach ($vendProduct->images as $image) {
            $poplets .= ($image->sizes->standard . ";");
          }

          $product->popletImages = $poplets;

          $formattedProducts[$vendProduct->handle]['xml'] = $product->asXML();
        }

        if (!empty($vendProduct->variant_options)) {
          $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ] = [];
          $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ]['productid'] = $vendProduct->id;

          if (array_key_exists( $vendProduct->id, $vendInventories)) {
            $count = $vendInventories[$vendProduct->id]->inventory_level;
          } else {
            $count = 0;
          }
          $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ]['count'] = $count;

          foreach ($vendProduct->variant_options as $variantOption) {
            $formattedProducts[$vendProduct->handle]['variants'][ $vendProduct->sku ]['variant_options'][ $variantOption->name ] = $variantOption->value;
          }
        }

      }

    }

    // data all ready for business catalyst processing
    // $formattedProducts = array_slice($formattedProducts,0, 5);

    $chunkSize = 1;

    for ($i=0;((count($formattedProducts)-$i)>0);$i+=$chunkSize) {

     $bcXMLProductList = simplexml_load_string("<productList></productList>");

      $formattedProductsChunk = array_slice($formattedProducts, $i, $chunkSize, true);
      $productsXML = '';

      foreach ($formattedProductsChunk as $key => $product) {
        //iterate through products in this batch
        // var_dump($key);

        //check whether product has variants or not
        if (!array_key_exists('variants', $product)) {
        // if no variants exist, then flatten product and add to string
          // var_dump($product);
          $productsXML .= $product['xml'];

          sxml_append($bcXMLProductList, simplexml_load_string( $product['xml'] ));
        } else {
        // if variants, then sort and build variants
          $variations = '<variations>';
          $attributeNames = [];
          $totalCount = 0;

          foreach ($product['variants'] as $variantCode => $variant) {
            if ($variant['count'] >= 0) {
              $totalCount += $variant['count'];
            }
            //iterate variants, build xml accumulate count
            if (empty($attributeNames)) {
              foreach ($variant['variant_options'] as $key => $value) {
                array_push($attributeNames,$key);
              }
            }

            $optionsString = '';
            foreach ($attributeNames as $attributeName) {
              $optionsString .= $variant['variant_options'][ $attributeName ];
              $optionsString .= ';';
            }
            // var_dump($variant['variant_options']);
            $variantXML = "<ProductVariation>
                        <id>0</id>
                        <options>{$optionsString}</options>
                        <code>{$variantCode}</code>
                        <enabled>true</enabled>
                        <inStock>{$variant['count']}</inStock>
                        <onOrder>0</onOrder>
                    </ProductVariation>";
            // var_dump($variantXML);

            $variations .= $variantXML;
          }
          //
          $variations .= '</variations>';
          $productXML = simplexml_load_string( $product['xml'] );
          $productXML->inStock = $totalCount;

          $productXML->addChild('hasVariations',"true");

          // $productXML->variations = simplexml_load_string($variations) ;

          sxml_append($productXML, simplexml_load_string($variations));

          // var_dump(simplexml_load_string($variations));
          // var_dump($productXML->asXML());
          $productsXML .= $productXML->asXML();


          sxml_append($bcXMLProductList, $productXML);
        } //end of variant logic
        // echo "\n + + + + + + + + + + + + + + + + + + \n";

      }
      $bcXMLProductListString = $bcXMLProductList->asXML();
      $bcXMLProductListString = substr($bcXMLProductListString, strpos($bcXMLProductListString, '?'.'>') + 2);
      $bcXML = simplexml_load_string("<?xml version=\"1.0\" encoding=\"utf-8\"?>
       <soap12:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap12=\"http://www.w3.org/2003/05/soap-envelope\">
         <soap12:Body>
           <Product_UpdateInsert xmlns=\"http://tempuri.org/CatalystDeveloperService/CatalystEcommerceWebservice\">
             <username>$EFUSION_USERNAME</username>
             <password>$EFUSION_PASSWORD</password>
             <siteId>$EFUSION_SITE_ID</siteId>{$bcXMLProductListString}
           </Product_UpdateInsert>
         </soap12:Body>
       </soap12:Envelope>");
       // var_dump($bcXML->asXML());
       $bcXML = $bcXML->asXML();
       $bcXML = substr($bcXML, strpos($bcXML, '?'.'>') + 2);
       efusionSoapy($bcXML);
    }
  }

  function exampleProduct () {
    global $EFUSION_SITE_ID, $EFUSION_USERNAME, $EFUSION_PASSWORD;
    $getProduct = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
               <soap12:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap12=\"http://www.w3.org/2003/05/soap-envelope\">
                 <soap12:Body>
                   <Product_Retrieve xmlns=\"http://tempuri.org/CatalystDeveloperService/CatalystEcommerceWebservice\">
                     <username>$EFUSION_USERNAME</username>
                     <password>$EFUSION_PASSWORD</password>
                     <siteId>$EFUSION_SITE_ID</siteId>
                     <productCode>1500M</productCode>
                   </Product_Retrieve>
                 </soap12:Body>
               </soap12:Envelope>";
    $productSoapy = efusionSoapy($getProduct);
    echo $productSoapy;
  }
