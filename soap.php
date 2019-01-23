<?php

//location of wsdl
$Webwsdl = 'https://leads.terminix-triad.com/WebLead.svc?singleWsdl';
$Zipwsdl = 'https://leads.terminix-triad.com/ZipCode.svc?singleWsdl';
$Contactwsdl = 'https://leads.terminix-triad.com/Contact.svc?singleWsdl';

$brwsdl = 'http://import.brassring.com/WebRouter/WebRouter.asmx?WSDL';

//try {
//
////attempt to create client in WSDL mode
//$contactclient = new SoapClient($Contactwsdl, array('trace' => TRUE));
//
//}
//catch (SoapFault $e) {
//
////client creation failed, trigger error using exception message as error content
//trigger_error($e->getMessage(), E_USER_WARNING);
//
////display an error message to the browser
//echo 'An error occurred while attempting to use the webclient x.co web service';
//
////exit the script
//exit;
//}
//
//
//try {
//
////attempt to create client in WSDL mode
//$webclient = new SoapClient($Webwsdl, array('trace' => TRUE));
//
//}
//catch (SoapFault $e) {
//
////client creation failed, trigger error using exception message as error content
//trigger_error($e->getMessage(), E_USER_WARNING);
//
////display an error message to the browser
//echo 'An error occurred while attempting to use the webclient x.co web service';
//
////exit the script
//exit;
//}
//
//try {
//
////attempt to create client in WSDL mode
//$zipclient = new SoapClient($Zipwsdl, array('trace' => TRUE));
//
//}
//catch (SoapFault $e) {
//
////client creation failed, trigger error using exception message as error content
//trigger_error($e->getMessage(), E_USER_WARNING);
//
////display an error message to the browser
//echo 'An error occurred while attempting to use the zipclient x.co web service';
//
////exit the script
//exit;
//}

$brwsdl = 'http://import.brassring.com/WebRouter/WebRouter.asmx?WSDL';
try {
    
    //attempt to create client in WSDL mode
    $client = new SoapClient($brwsdl, array('trace' => TRUE));
    
}
catch (SoapFault $e) {
    
    //client creation failed, trigger error using exception message as error content
    trigger_error($e->getMessage(), E_USER_WARNING);
    
    //display an error message to the browser
    echo 'An error occurred while attempting to use the zipclient x.co web service';
    
    //exit the script
    exit;
}

$xml = '<Envelope version="01.00">
    <Sender>
        <Id>123456</Id>
        <Credential>26286</Credential>
    </Sender>
    <TransactInfo transactId="1" transactType="data">
        <TransactId>07/16/2018</TransactId>
        <TimeStamp>12:00:00 AM</TimeStamp>
    </TransactInfo>
    <Unit UnitProcessor="SearchAPI">
        <Packet>
            <PacketInfo packetType="data">
                <packetId>1</packetId>
            </PacketInfo>
            <Payload>
                <InputString>
                    <ClientId>25017</ClientId>
                    <SiteId>5172</SiteId>
                    <PageNumber>1</PageNumber>
                    <OutputXMLFormat>0</OutputXMLFormat>
                    <HotJobs/>
                    <JobMatchCriteriaText>"#DEPTOFMEDICINE"</JobMatchCriteriaText>
                    <DatePosted>ALL</DatePosted>
                </InputString>
            </Payload>
        </Packet>
    </Unit>
</Envelope>';


$response = $client->route(array('inputXml' => $xml));
echo '<pre>';
print_r($response);
echo '</pre>';

//echo 'ContactClient:<br>';
//echo '<pre>'; print_r($contactclient->__getFunctions());echo '</pre>';

//echo 'WebClient:<br>';
//echo '<pre>'; print_r($webclient->__getFunctions());echo '</pre>';
//echo 'Zipclient:<br>';
//echo '<pre>'; print_r($zipclient->__getFunctions());echo '</pre>';


/*
$response = $zipclient->Lookup(array('zipCode'=>'27101'));

echo '<pre>';
print_r($response);
echo '</pre>';
*/

/*
$webLead = array(
    'Vid' => '9361062',
    'Lid' => '15',
    'Password' => '9RHgDcULyCav',
    'FirstName' => 'Test',
    'LastName' => 'Real Green Analytics',
    'StreetAddress' => '101 Test Ave',
    'City' => 'Testville',
    'State' => '',
    'PostalCode' => '27601',
    'HomePhAreaCode' => '',
    'HomePhNumber' => '9195555555', 
    'EveningPhAreaCode' => '',
    'EveningPhNumber' => '',
    'EmailAddress' => 'wmcmillian@coalmarch.com',
    'Service' => '',
    'Comments' => 'Nulla facilisi. Aliquam bibendum fringilla enim sit amet lacinia. Vestibulum tincidunt bibendum diam at scelerisque.',
    'BestTime' => '',
    'CompanyName' => '',
    'MarketingChannel' => '',
    'MarketingPartner' => '',
    'OfferCode' => '',
    'AdName' => '',
    'PartnerProgram' => '',
    'Campaign' => '',
    'Keyword' => '',
    'AdWords' => '',
    'AdType' => '',
    'OfferDescription' => '',
    'Commercial' => '',
);


$webresponse = $webclient->ProcessLead(array('lead' => $webLead));

echo '<pre>';
print_r($webresponse);
echo '</pre>';
*/