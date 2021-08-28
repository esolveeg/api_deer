<?php

use Illuminate\Support\Facades\Route;
use App\Author;
use App\Group;
use App\Product;

Route::get('/', function () {
    $file = file_get_contents(public_path('data/products.json'));
        $products = json_decode($file);
        $chunks = array_chunk($products, 10);
        foreach ($chunks as $chunk) {
            foreach ($chunk as $pr) {
                $pro = Product::where('isbn' , $pr->ISBN)->first();
                if($pro == null){
                    $product = [
                        "isbn" => $pr->ISBN,
                        "productName" => $pr->ItemName,
                        "price" => $pr->POSPP,
                        "productImage" => 'o',
                        "productDesc" => "لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات . ديواس أيوتي أريري دولار إن ريبريهينديرأيت فوليوبتاتي فيلايت أيسسي كايلليوم دولار أيو فيجايت  نيولا باراياتيور. أيكسسيبتيور ساينت أوككايكات كيوبايداتات نون بروايدينت ,سيونت ان كيولباكيو أوفيسيا ديسيريونتموليت انيم أيدي ايست لابوريوم",
                        "latest" => true,
                        "featured" => true,
                        "bestseller" => true,
                    ];
                    $groups = explode('/', $pr->group);
                    $author = Author::create(['authorName' => $pr->author]);
                    
                    $parent = null;
                    $groupId = null;
                    foreach ($groups as $group) {
                        $rec = [
                            'groupName' => $group,
                            'featured' => true,
                            'home' => true,
                            'groupId' => $parent,
                        ];
    
                        $q = Group::create($rec);
                        $parent = $q->id;
                        $groupId = $q->id;
                    }
                    $product['groupId'] = $groupId;
                    $product['authorId'] = $author->id;
    
                    Product::Create($product);
                }

                
            }
        }
    return view('welcome');
});
