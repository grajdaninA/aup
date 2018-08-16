<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class A {

    function pc() {
        echo 'класс а'; 
    }

}

class B extends A {}
class C extends A {}

$b = new B();
$c = new C();

$b->pc();
$c->pc();
        