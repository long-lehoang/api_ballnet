<?php
namespace App\Repository\Employee;

interface IEmployeeRepo{
   public function isValidUser($credentials);
   public function revokeToken();
   public function updatePassword($request);
   public function authByPassword($password);
   public function getCurrentUser();
   public function updateProfile($request);
}