# Magento 2 Special page for customer discount according to choose item each category.
Description : Magento 2.x Custom discount page for producuts. if user select one product then dicount 0 if customer select 2 product discount 5% if 3 then 10% if 4 then 15% if 5 then 20%. Single item quantity should be 1 and only added from http://yourdomain.com/offer page. 

## Manual Installation guide 
1.	You have to put extension in app/code/Pkgroup/Customeroffer. After that you need to enable module via below commands. 
### Steps: 
#### php bin/magento module:enable Pkgroup_Customeroffer
#### php bin/magento setup:upgrade --keep-generated
#### php bin/magento setup:di:compile 
#### php bin/magento setup:static-content:deploy -f

Congratulation extension is installed and now you are ready to use. 

## Composer Installation guide
1.	Download package via composer with below composer command and follow up below steps for installation. 

### Composer package download:
#### composer require pkgroup/customeroffer
### Steps:
#### php bin/magento module:enable Pkgroup_Customeroffer
#### php bin/magento setup:upgrade --keep-generated
#### php bin/magento setup:di:compile 
#### php bin/magento setup:static-content:deploy -f

