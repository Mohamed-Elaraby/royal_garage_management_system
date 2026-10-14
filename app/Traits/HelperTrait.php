<?php


namespace App\Traits;


use App\Models\Statement;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\This;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

trait HelperTrait
{
    protected $data;
    protected $image_name;
    protected $image_path;
    protected $image_type;
    protected $personal_name;
    protected $personal_define;
    protected $disk_name;
    protected $path_want_be_delete;
    protected $old_image_path;
    protected $old_image_name;
    protected $image_width;
    protected $image_height;
    protected $old_data_object;
    protected $new_path_want_be_rename;
    protected $check_number;
    protected $check_id;
    protected $SVGImageData;
    protected $file_name;
    protected $file_path;
    protected $file_request;
    protected $file_extension;
    protected $file_full_name;
    protected $seller_name;
    protected $vat_number;
    protected $invoice_date;
    protected $invoice_total_amount;
    protected $invoice_total_vat;

    ####### Begin uploadImageProcessing Function #########
    public function uploadImageProcessing($_photoName = null, $_personalDefine, $_photoType, $_personalName, $_diskName, $_photoWidth, $_photoHeight, $_oldDataObject = null)
    {
        $this -> image_type         = $_photoType;
        $this -> personal_name      = $_personalName;
        $this -> personal_define    = $_personalDefine;
        $this -> disk_name          = $_diskName;
        $this -> image_width        = $_photoWidth;
        $this -> image_height       = $_photoHeight;
        $this -> old_data_object    = $_oldDataObject;
        $this -> old_image_name     = $this -> old_data_object?$this -> old_data_object -> image_name:'default.png';
        $this -> old_image_path     = $this -> old_data_object?$this -> old_data_object -> image_path:null;
        $this -> image_name         = 'default.png';
        $this -> image_path         = $_photoName?$this -> personal_define .DIRECTORY_SEPARATOR. $this -> image_type .DIRECTORY_SEPARATOR. $this -> personal_name:$this -> old_image_path;

        if ($this -> old_data_object){ // This Condition Only Use On Update Case If old_data_object Not Equal Null

            ############################  Check Request Has Image Do It ############################
            #   1- [ Get Old Image Path From Database ]
            #   2- [ Convert Path To Array ]
            #   3- [ Delete Last Part From Path ]
            #   4- [ Save New Path In Variable ]
            #   5- [ Get Old Personal Name And Save In Variable ]
            ############################  Check Request Has Image Do It ############################

            $path_array                         = explode(DIRECTORY_SEPARATOR,  $this -> old_image_path); // array
            $this -> path_want_be_delete        = $this -> old_image_path?$path_array[0] .DIRECTORY_SEPARATOR.  $path_array[1] .DIRECTORY_SEPARATOR.  $path_array[2]:'';
            $this -> new_path_want_be_rename    = $this -> old_image_path?$path_array[0] .DIRECTORY_SEPARATOR.  $path_array[1] .DIRECTORY_SEPARATOR. $this -> personal_name:'';
            $old_personal_name                  = $this -> old_data_object -> name;

            if($_photoName){
                ############################  Check Request Has Image Do It ############################
                #   1- [ Check Image Path If Exists Delete The Path ]
                ############################  Check Request Has Image Do It ############################
                if (Storage::disk($this -> disk_name)->exists($this -> path_want_be_delete))
                    Storage::disk($this -> disk_name)->deleteDirectory($this -> path_want_be_delete);

            }else{

                ############################  Check Request Dos Not Have Image Do It ############################
                #   1- [ Check Old Personal Name Not Equal New Personal Name ]
                #   2- [ Check Image Path If Exists Rename The Personal name Directory To New Personal Name ]
                #   3- [ Update Value Image Path Variable To New Path  ]
                ############################  Check Request Dos Not Have Image Do It ############################

                if ($old_personal_name !== $this -> personal_name){
                    if (Storage::disk($this -> disk_name)->exists($this -> path_want_be_delete))
                        Storage::disk($this -> disk_name)->move($this -> path_want_be_delete, $this -> new_path_want_be_rename);
                    $this -> image_path = $this -> new_path_want_be_rename;
                }
            }
        }

        if ($_photoName){  // This Condition Use On Create And Update Case
            ############################  Check Request Has Image Do It ############################
            #   1- [ Check Image Path If Not Exists Make This is Path ]
            #   2- [ Make And upload Image In To New Path  ]
            ############################  Check Request Has Image Do It ############################
            if (!Storage::disk($this -> disk_name)->exists($this -> image_path))
            Storage::disk($this -> disk_name)->makeDirectory($this -> image_path);

            if (is_array($_photoName)) {
                $myArray = [];
                foreach ($_photoName as $photo)
                {
                    $this -> image_name         = $photo?$photo -> hashName():$this -> old_image_name;
                    $image = Image::make($photo);
                    $image -> resize($this -> image_width, $this -> image_height);
                    $image -> save(public_path('storage' .DIRECTORY_SEPARATOR. $this -> image_path.DIRECTORY_SEPARATOR.$this -> image_name));
                    $m = ['image_path' => $this -> image_path, 'image_name' => $this -> image_name];
                    array_push($myArray, $m);

                }
                return $myArray;
            } else {
                    $this -> image_name         = $_photoName?$_photoName -> hashName():$this -> old_image_name;
                    $image = Image::make($_photoName);
                    $image -> resize($this -> image_width, $this -> image_height);
                    $image -> save(public_path('storage' .DIRECTORY_SEPARATOR. $this -> image_path.DIRECTORY_SEPARATOR.$this -> image_name));

            }

        }
        return ['image_path' => $this -> image_path, 'image_name' => $this -> image_name];

    }
    ####### End uploadImageProcessing Function #########

    ####### Begin uploadFileProcessing Function #########

    public function uploadFileProcessing($_fileRequest, $_diskName, $_checkNumber, $_fileName , $_filePath, $_fileExtension)
    {
        $this->file_request = $_fileRequest;
        $this->disk_name = $_diskName;
        $this->check_number = $_checkNumber;
        $this->file_name = $_fileName;
        $this->file_path = $_filePath;
        $this->file_extension = $_fileExtension;
        $this->file_full_name = $this->file_name .'.'. $this->file_extension;

        /* upload file */
        $this->file_request -> storeAs('public/'.$this->file_path,$this->check_number.DIRECTORY_SEPARATOR.$this->file_full_name);

        return ['path' => $this -> file_path.DIRECTORY_SEPARATOR.$this->check_number, 'name' => $this -> file_full_name, 'extension' => $this -> file_extension];
    }

    ####### End uploadFileProcessing Function #########

    ####### Begin deleteImageHandel Function #########
    /**
     * @param string $storageDiskName                       Example public Or S3
     * @param string $pathOfTheDirectoryYouWantToDelete     Example user/profile/username -> [ Mohamed ]
     */

    public function deleteImageHandel($storageDiskName, $pathOfTheDirectoryYouWantToDelete)
    {
        $this -> disk_name              = $storageDiskName;
        $allPath = collect($pathOfTheDirectoryYouWantToDelete)->toArray();
        foreach ($allPath as $path) {

            $this -> path_want_be_delete    = collect($path)-> toArray();

            if (array_key_exists(0, $this->path_want_be_delete)){

                unset($this -> path_want_be_delete[0]);
                $this -> path_want_be_delete['image_path'] =  $pathOfTheDirectoryYouWantToDelete;
            }
            // Check profile picture Of Doctor Exists And Not Default Image
            if (Storage::disk($this -> disk_name) ->exists($this -> path_want_be_delete['image_path'])){
                // Remove Picture And Folder For The Doctor Until Doctor Name Folder
                Storage::disk($this -> disk_name) ->deleteDirectory($this -> path_want_be_delete['image_path']);

            }

        }
    }
    ####### End deleteImageHandel Function #########

    ####### Begin uploadSVGImage Function #########
    public function uploadSVGImage($check_id, $SVGImageData, $_personalDefine, $_photoType, $_personalName, $_diskName)
    {

        $this -> personal_define    = $_personalDefine;
        $this -> image_type         = $_photoType;
        $this -> personal_name      = $_personalName;
        $this -> disk_name          = $_diskName;
        $this -> check_id           = $check_id;
        $this -> SVGImageData       = base64_decode($SVGImageData);
        $this -> image_name         = md5(date("dmYhisA"));
        $this -> image_path         = $this -> personal_define.DIRECTORY_SEPARATOR.$this -> image_type.DIRECTORY_SEPARATOR.$this -> personal_name;

        //Location to where you want to created sign image
        $full_path = public_path('storage'.DIRECTORY_SEPARATOR.$this -> personal_define.DIRECTORY_SEPARATOR.$this -> image_type.DIRECTORY_SEPARATOR.$this -> personal_name.DIRECTORY_SEPARATOR.$this -> image_name.'.png');
        if (!Storage::disk($this -> disk_name)->exists($this -> image_path)){
            Storage::disk($this -> disk_name)->makeDirectory($this -> image_path);
        }
        $image = Image::make($SVGImageData);
        $image -> save($full_path);

        return ['image_path' => $this -> image_path, 'image_name' => $this -> image_name.'.png'];
    }
    ####### End uploadSVGImage Function #########

    ####### Begin Set QR Code Function #########
    public static function SETQRCODE ($seller_name, $vat_number, $invoice_date, $invoice_total_amount, $invoice_total_vat)
    {
//        $this -> seller_name                = $seller_name;
//        $this -> vat_number                 = $vat_number;
//        $this -> invoice_date               = $invoice_date;
//        $this -> invoice_total_amount       = $invoice_total_amount;
//        $this -> invoice_total_vat          = $invoice_total_vat;
        $generatedString = GenerateQrCode::fromArray([
            new Seller($seller_name), // seller name
            new TaxNumber($vat_number), // seller tax number
            new InvoiceDate($invoice_date), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount($invoice_total_amount), // invoice total amount
            new InvoiceTaxAmount($invoice_total_vat) // invoice tax amount
        ])->render();

        return $generatedString;
    }
    ####### End Set QR Code Function #########

    ####### Begin insert to client transaction with relation Function #########

    /**
     * @param object $relatedModel                          Model Object
     * @param array $data
     * [
     * total_amount,
     * details,
     * amount_paid,
     * amount_paid_bank,
     * amount_paid_add_to_client_balance,
     * amount_due,
     * transaction_date,
     * transaction_type,
     * debit,
     * credit,
     * user_id,
     * client_id,
     * sale_order_id,
     * sale_order_return_id,
     * client_payment_id,
     * client_collecting_id
     * ]
     */
    public function insertToClientTransaction($relatedModel, $data = [])
    {
        $this -> data = array_merge([], $data);
        $relatedModel ->clientTransactions()->create($this -> data);
    }
    ####### End insert to client transaction with relation Function #########


    ####### Start insert to supplier transaction with relation Function #########
    /**
     * @param object $relatedModel                          Model Object
     * @param array $data
     * [
     * total_amount,
     * details,
     * amount_paid,
     * amount_paid_bank,
     * amount_paid_subtract_from_supplier_balance,
     * amount_due,
     * transaction_date,
     * transaction_type,
     * debit,
     * credit,
     * user_id,
     * supplier_id,
     * purchase_order_id,
     * purchase_order_return_id,
     * supplier_payment_id,
     * supplier_collecting_id
     * ]
     */
    public function insertToSupplierTransaction($relatedModel, $data = [])
    {
        $this -> data = array_merge([], $data);
        $relatedModel ->supplierTransactions()->create($this -> data);
    }
    ####### End insert to supplier transaction with relation Function #########


    ####### Begin insert to statement with relation Function #########

    /**
     * @param object $relatedModel                          Model Object
     * @param array $data
     * [
        * imports_cash
        * imports_network
        * imports_bank_transfer
        * card_details_hand_labour
        * card_details_new_parts
        * card_details_used_parts
        * card_details_tax
        * expenses_cash
        * expenses_network
        * custody_administration_cash
        * custody_administration_network
        * cash_to_administration
        * advances_and_salaries_cash
        * advances_and_salaries_network
        * notes
        * sale_order_id
        * client_payment_id
        * client_collecting_id
        * expenses_id
        * purchase_order_id
        * supplier_payment_id
        * supplier_collecting_id
        * sale_order_return_id
        * purchase_order_return_id
        * advance_id
        * reward_id
        * discount_id
        * employee_salary_log_id
        * branch_id
     * ]
     */
    public function insertToStatement($relatedModel, $data = [])
    {
        $this -> data = array_merge([], $data);
        $relatedModel ->statements()->create($this -> data);
    }
    ####### End insert to statement with relation Function #########


    ####### Begin insert to expenses with relation Function #########

    /**
     * @param object $relatedModel                          Model Object
     * @param array $data
     * [
     * imports_cash
     * imports_network
     * imports_bank_transfer
     * card_details_hand_labour
     * card_details_new_parts
     * card_details_used_parts
     * card_details_tax
     * expenses_cash
     * expenses_network
     * custody_administration_cash
     * custody_administration_network
     * cash_to_administration
     * advances_and_salaries_cash
     * advances_and_salaries_network
     * notes
     * sale_order_id
     * client_payment_id
     * client_collecting_id
     * expenses_id
     * purchase_order_id
     * supplier_payment_id
     * supplier_collecting_id
     * sale_order_return_id
     * purchase_order_return_id
     * advance_id
     * reward_id
     * discount_id
     * employee_salary_log_id
     * branch_id
     * ]
     */
    public function insertToExpenses($relatedModel, $data = [])
    {
        $this -> data = array_merge([], $data);
        $relatedModel ->expenses()->create($this -> data);
    }
    ####### End insert to expenses with relation Function #########
}
