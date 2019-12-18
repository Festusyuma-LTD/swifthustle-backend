<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test</title>

    </head>
    <body>
        <form >
            <script src="https://js.paystack.co/v1/inline.js"></script>
            <button type="button" onclick="payWithPaystack()"> Pay </button> 
        </form>

        <script>
           async function payWithPaystack(){
              var handler = PaystackPop.setup({
                key: 'pk_test_87b43cb03070ea4c4f584656222db9aa18ff7472',
                email: 'ridbir@email.live',
                amount: 10000,
                currency: "NGN",
                ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                metadata: {
                   custom_fields: [
                      {
                          display_name: "Mobile Number",
                          variable_name: "mobile_number",
                          value: "+2348012345678"
                      }
                   ]
                },
                callback: function(response){
                    //alert('success. transaction ref is ' + response.reference);
                    console.log(response);
                    //console.log({"reference" : response.reference})
                    const url = 'http://127.0.0.1:8000/api/user/wallet/fund';

                    let data = {
                        reference : response.reference
                    };

                    console.log(JSON.stringify(data))

                    let fetchData = {
                        method : 'POST',
                        body : JSON.stringify(data),
                        headers :
                        {
                            Authorization : `Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNWMzMDc5MzJhNGNiNDNjYzJkNDc1N2EwYjFjMTdiZjc5ZWQ5ZTQ3NTg1ZTM4YmRhZWQ2ZWYyMWU4NDg5Njg1Zjg0ZjNkODE2MzlhY2JjNjUiLCJpYXQiOjE1NzYzNDAzNjQsIm5iZiI6MTU3NjM0MDM2NCwiZXhwIjoxNjA3OTYyNzY0LCJzdWIiOiI2Iiwic2NvcGVzIjpbXX0.Cqndqua3dekqriHrJVCo2U9Rg1B6huYU4F7M-qk9LAqGDXYqXjWkkgqRceGCqww4f7b2nll6HeRLcr2Idnk7D0VG4L_NiJIICGMoVs1tqf9uRf-ArGBxEqI1kUMYgGqbk5eFSYFRIpVdYS6W53j5tKh-2Q3t7r2myAl6lVtOZTOlHxQA6_gKrdrH8110rblGP1_rlhRYcbCkaGF0KDbxWAhw4IEH-Jyg5c9Wvw1m6QTxRQsCBU4kE1rXBeRJYUw9WOM3fv-6OGFBqZ8branSjxnYjBitRgDg7DksVJWXPFwgGqlRd-YxdwOzSH2VXyfqr4JxHAzLzNh-zpafEAWBogcDO5U7SkNhfM9vo6ZiRjVnzNdC4eVmxvpfNXrMk-LhdHtyYjdMN3JVjd2fDK6fw-rHY4EE3SwHpyC-3nHrhsBjrfo0jpeqLIVhArU1rfTQcVWoXht1rhKcZlaJEZQhi9F3sPLSruRVsgpIQSU1dGaNTC2itpyqs-9P_4t86C8-fbl7Hu45aOWHSGWRhnJLddkJknY0CurMSggQ74pkhXA6mIA3Gqdr0P96PN9kXzD4CPp7NwfX2BufPnt12Z4aFtG2jn01xK7uuwAxTVuZFvcq_i9ZWhZmiZL5xlzeUQNSDDZ4GV6Yp8gu1hn6iJGdL0LNiBH3cdpt5B74ob--50o`
                        } 
                    }

                    fetch(url, fetchData).then((res) => res.json()).then(function(data){
                        console.log(data)
                    }).catch(function(err){
                        console.log(err)
                    })
                },
                onClose: function(){
                    alert('window closed');
                }
              });
              handler.openIframe();
            }
          </script>
    </body>

</html>
