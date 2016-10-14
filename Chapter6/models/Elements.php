<?php 
/**
 * Loudbite.com Form Elements.
 *
 */
class Elements 
{

    /**
     * Create email text field
     *
     * @return Zend_Form_Element_Text
     */
    public function getEmailTextField()
    {

        //Create Text Field Object.
        $emailElement = new Zend_Form_Element_Text('email');
        $emailElement->setLabel('Email:');
        $emailElement->setRequired(true);

        //Add Validator
        $emailElement->addValidator(new Zend_Validate_EmailAddress());

        //Add Filter
        $emailElement->addFilter(new Zend_Filter_HtmlEntities());
        $emailElement->addFilter(new Zend_Filter_StripTags());

        return $emailElement;

    }


    /**
     * Create password text field
     *
     * @return Zend_Form_Element_Password
     */
    public function getPasswordTextField()
    {

        //Create Password Object.
        $passwordElement = new Zend_Form_Element_Password('password');
        $passwordElement->setLabel('Password:');
        $passwordElement->setRequired(true);

        //Add Validator
        $passwordElement->addValidator
                          (
                           new Zend_Validate_StringLength(6,20)
                          );

        //Add Filter
        $passwordElement->addFilter(new Zend_Filter_HtmlEntities());
        $passwordElement->addFilter(new Zend_Filter_StripTags());

        return $passwordElement;

    }


    /**
     * Create username text field.
     * 
     * @return Zend_Form_Element_Text
     */
    public function getUsernameTextField()
    {

        $usernameElement = new Zend_Form_Element_Text('username');
        $usernameElement->setLabel('Username:');
        $usernameElement->setRequired(true);

        //Add validator 
        $usernameElement->addValidator
                          (
                            new Zend_Validate_StringLength(6, 20)
                          );

        //Add Filter
        $usernameElement->addFilter(new Zend_Filter_StripTags());
        $usernameElement->addFilter(new Zend_Filter_HtmlEntities());
        $usernameElement->addFilter(new Zend_Filter_StringToLower());

        return $usernameElement;

    }

}
