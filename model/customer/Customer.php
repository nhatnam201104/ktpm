<?php
       class Customer{
        protected $id;
        protected $role_id;
        protected $name;
        protected $mobile;
        protected $email;
        protected $password;
        protected $updated_at;
        protected $created_at;
        protected $is_active;
        protected $address;
        protected $ward_id;

        function __construct($id, $role_id, $name, $mobile, $email, $password, $updated_at, $created_at, $is_active, $address, $ward_id){
            $this->id = $id;
            $this->role_id = $role_id;
            $this->name = $name;
            $this->mobile = $mobile;
            $this->email = $email;
            $this->password = $password;
            $this->updated_at = $updated_at;
            $this->created_at = $created_at;
            $this->is_active = $is_active;
            $this->address = $address;
            $this->ward_id = $ward_id;
        }

        function getId(){
            return $this->id;
        }

        function getRoleID(){
            return $this->role_id;
        }

        function getName(){
            return $this->name;
        }
        
        function getMobile(){
            return $this->mobile;
        }
        
        function getEmail(){
            return $this->email;
        }

        function getPassword(){
            return $this->password;
        }

        function getUpdatedAt(){
            return $this->updated_at;
        }

        function getCreatedAt(){
            return $this->created_at;
        }
        
        function getIsActive(){
            return $this->is_active;
        }

        function getHousenumberStreet(){
            return $this->address;
        }

        function getWardId(){
            return $this->ward_id;
        }

        function setRoleId($role_id){
            $this->role_id = $role_id;
            return $this;
        }

        function setName($name){
            $this->name = $name;
            return $this;
        }

        function setMobile($mobile){
            $this->mobile = $mobile;
            return $this;
        }
        
        function setEmail($email){
            $this->email = $email;
            return $this;
        }

        function setPassword($password){
            $this->password = $password;
            return $this;
        }
        
        function setUpdatedAt($updated_at){
            $this->updated_at = $updated_at;
            return $this;
        }

        function setCreatedAt($created_at){
            $this->created_at = $created_at;
            return $this;
        }

        function setIsActive($is_active){
            $this->is_active = $is_active;
            return $this;
        }

        function setAddress($address){
            $this->address = $address;
        }

        function setWardId($ward_id){
            $this->ward_id = $ward_id;
            return $this;
        }


        function getWard() {
            $wardRepository = new WardRepository();
            $ward = $wardRepository->find($this->ward_id);
            return $ward;
        }

        
    
        // function getAddress() {
        //     $address1 = "";
        //     if ($this->address) {
        //         $address1 = $this->getHousenumberStreet();
        //     }
    
        //     if ($this->ward_id) {
        //         $ward = $this->getWard();
        //         $district = $ward->getDistrict();
        //         $province = $district->getProvince();
        //         $address1 .= ", " . $ward->getName() . ", " . $district->getName() . ", " . $province->getName();
        //     }
    
        //     return $address1;
        // }
        function getAddress(){
            return $this->address;
        }


    }


?>