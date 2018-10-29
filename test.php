<?php
$url = "http://import.brassring.com/WebRouter/WebRouter.asmx/route";
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

$headers = array(
    "Host: import.brassring.com",
//    "Content-Type: application/x-www-form-urlencoded",
//    "Content-Length: " . strlen($xml),
//    "Connection: keep-alive",
);

$payload = array(
    'inputXml' => $xml
);

$params = http_build_query($payload, null, '&');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url . "?$params");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

echo '<pre>';
print_r($params);
echo '</pre>';

$data = curl_exec($ch);
if(curl_errno($ch)) {
    echo '<pre>';
    print_r($httpInfo);
    echo '</pre>';
    //print curl_error($ch);
}
else {
    $info = curl_getinfo($ch);
    echo '<pre>';
    print_r($info);
    echo '</pre>';
    curl_close($ch);
}

echo '<pre>';
print_r($data);
echo '</pre>';

$echo = module_exists("advagg") ? "1" :  "0";echo $echo;