<?php

    require '/vagrant/vendor/autoload.php';

    use Aws\Sns\SnsClient; 
    use Aws\Exception\AwsException;

    # we should add a check that the user is logged in first before using this script.

    #Subscribe Email Address to SNS Topic.
    # I used the aws php sdk examples github repo as a guide.
    # https://github.com/awsdocs/aws-doc-sdk-examples/tree/main/php/example_code/sns

    $SnSclient = new SnsClient([
        'profile' => 'default',
        'region' => 'us-east-1',
        'version' => '2010-03-31'
    ]);

    $topic = 'arn:aws:sns:us-east-1:898431862435:StocktakeCloud';

    # get Subscriptions
    try {
        $subscriptions = $SnSclient->listSubscriptionsByTopic([
            'TopicArn' => $topic,
        ]);
    }
    catch (AwsException $e) {
        // output error message if fails
        error_log($e->getMessage());

        echo "Could not get current email. Credentials may have expired.";
    }

    if (count($subscriptions['Subscriptions']) > 0) {
        try {
            foreach ($subscriptions['Subscriptions'] as $sub) {
                if ($sub['SubscriptionArn'] != 'PendingConfirmation')
                $unsub = $SnSclient->unsubscribe([
                    'SubscriptionArn' => $sub['SubscriptionArn'],
                ]);
            }
        }
        catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
            echo "Could not unsubcribe current email.";
        }
    }
    
    $protocol = 'email';
    $endpoint = $_REQUEST['email'];

    try {
        $result = $SnSclient->subscribe([
            'Protocol' => $protocol,
            'Endpoint' => $endpoint,
            'ReturnSubscriptionArn' => true,
            'TopicArn' => $topic,
        ]);
    } 
    catch (AwsException $e) {
        // output error message if fails
        error_log($e->getMessage());

        #display error to user.
        echo "Could not subcribe email.";
    } 
    
    echo "<script>location.href='../success.html'</script>";

?>