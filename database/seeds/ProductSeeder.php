<?php

use App\Author;
use App\Group;
use App\Product;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {     
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
                        "productImage" => 'http://exprostudio.com/html/book_library/images/books/img-03.jpg',
                        "productDesc" => "لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات . ديواس أيوتي أريري دولار إن ريبريهينديرأيت فوليوبتاتي فيلايت أيسسي كايلليوم دولار أيو فيجايت  نيولا باراياتيور. أيكسسيبتيور ساينت أوككايكات كيوبايداتات نون بروايدينت ,سيونت ان كيولباكيو أوفيسيا ديسيريونتموليت انيم أيدي ايست لابوريوم",
                        "latest" => true,
                        "featured" => true,
                        "bestseller" => true,
                    ];
                    $groups = explode('/', $pr->group);
                    $author = Author::create(['authorName' => $pr->author]);
                    
                    $groupId = null;
                    

                    //check if we only have one group
                    // create the group
                    //make the prouct group id is the new created group id 
                    if(count($groups) == 1){
                        $dbGroup = Group::where('groupName' , $groups[0])->first();
                        $rec = [
                            'groupName' => $groups[0],
                            'featured' => false,
                            'home' => false,
                            'groupId' => null,
                        ];
                        try{
                            $dbGroup = Group::create($rec);
                        } catch(Exception $error){
                            print($error);
                        }
                        $groupId = $dbGroup->id;
                        
                    } else{
                        // if we rechec this point that means we have more than on category
                        // i will limit this to just two for the sake of simplicity
                        // then we will create the first group as a parent 
                        // and a second group as a child to this parent
                        // and finally we will atach the prouct group id to the child's id
                        $dbParentGroup = Group::where('groupName' , $groups[0])->first();
                        $dbChildGroup = Group::where('groupName' , $groups[1])->first();
                        $parentId = null;
                        $parentRec = [
                            'groupName' => $groups[0],
                            'featured' => false,
                            'home' => false,
                            'groupId' => null,
                        ];
                       
                        try{
                            $dbParentGroup = Group::create($parentRec);
                        } catch(Exception $error){
                            print($error);
                        }
                        $parentId = $dbParentGroup->id;
                        $childRec = [
                            'groupName' => $groups[1],
                            'featured' => false,
                            'home' => false,
                            'groupId' => $parentId,
                        ];
                        try{
                            $dbChildGroup = Group::create($childRec);
                        } catch(Exception $error){
                            print($error);
                        }
                        $groupId = $dbChildGroup->id;
                    }
                    $product['groupId'] = $groupId;
                    $product['authorId'] = $author->id;
    
                    Product::Create($product);
                }

                
            }
        }
        
      }
}
