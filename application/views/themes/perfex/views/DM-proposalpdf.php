<?php
$dimensions = $pdf->getPageDimensions();

$pdf_logo_url = pdf_logo_url();
//$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $pdf_logo_url, 0, 1, false, true, 'L', true);

//$pdf->ln(4);
// Get Y position for the separation
//$y            = $pdf->getY();



$proposal_info = '<div style="color:#424242;">';
    $proposal_info .= format_organization_info();
$proposal_info .= '</div>';

//$pdf->writeHTMLCell(($swap == '0' ? (($dimensions['wk'] / 2) - $dimensions['rm']) : ''), '', '', ($swap == '0' ? $y : ''), $proposal_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);

/*echo base_url();
die;*/

//$rowcount = max(array($pdf->getNumLines($proposal_info, 80)));

// Proposal to
$client_details = '<b>'._l('proposal_to').'</b>';
$client_details .= '<div style="color:#424242; float:right;">';
    $client_details .= format_proposal_info($proposal,'pdf');
$client_details .= '</div>';

//$pdf->writeHTMLCell(($dimensions['wk'] / 2) - $dimensions['lm'], $rowcount*7, '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);

//$pdf->ln(6);

$proposal_date = _l('proposal_date') . ': ' . _d($proposal->date);
$open_till = '';

if(!empty($proposal->open_till)){
    $open_till = _l('proposal_open_till'). ': ' . _d($proposal->open_till) . '<br />';
}

/*$c1 = $value  = get_custom_field_value($proposal->id, '1', 'proposal');
echo $c1;
die;*/

$customFieldsProposals = get_custom_fields('proposal', $whereCF);
        
 //print_r($customFieldsProposals);
 $custfield = [];
        foreach ($customFieldsProposals as $field) {
            $value  = get_custom_field_value($proposal->id, $field['id'], 'proposal');
            //echo $value;
            //echo "<br>";
            array_push($custfield, [$field['name'] => $value]);
            $format = _info_format_custom_field($field['id'], $field['name'], $value, $format);
        }
        //print_r($custfield);
        $Search_Traffic_within_1 = $custfield[0]['Search Traffic within-1'];
        
        $Goal_is_to_work_with_2 = $custfield[1]['Goal is to work with-2'];
        
        $Generate_Approx_Qty_4 = $custfield[2]['Generate Approx Qty-4'];
        
        $Guesstimated_Leads_5 = $custfield[3]['Guesstimated Leads-5'];
        
        $Need_to_Generate_6 = $custfield[4]['Need to Generate - 6'];
        
        $Increas_Trafic_From_10 = $custfield[5]['Increas Trafic From-10'];
      
        $Increase_Traffic_To_11 = $custfield[6]['Increase Traffic To-11'];
        
        $custField_3 = $Goal_is_to_work_with_2/12;
        $custField_8 = $Guesstimated_Leads_5;
        
//die;

$login_data = get_staff_full_name1();
//echo $login_data;
$char = "(";

$pos1 = strpos($login_data,$char);

$login_full_name = substr($login_data,0,$pos1);
//echo $login_full_name;
$login_email = substr($login_data,$pos1+1,-1);
//echo "<br>";
//echo $login_email;
//die;


$item_width = 38;
// If show item taxes is disabled in PDF we should increase the item width table heading
$item_width = get_option('show_tax_per_item') == 0 ? $item_width+15 : $item_width;
$custom_fields_items = get_items_custom_fields_for_table_html($proposal->id,'proposal');

// Calculate headings width, in case there are custom fields for items
$total_headings = get_option('show_tax_per_item') == 1 ? 4 : 3;
$total_headings += count($custom_fields_items);
$headings_width = (100-($item_width+6)) / $total_headings;

// The same language keys from estimates are used here
$qty_heading = _l('estimate_table_quantity_heading');
if($proposal->show_quantity_as == 2){
    $qty_heading = _l('estimate_table_hours_heading');
} else if($proposal->show_quantity_as == 3){
    $qty_heading = _l('estimate_table_quantity_heading') .'/'._l('estimate_table_hours_heading');
}

// Header
$items_html = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="8">';

$items_html .= '<tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">';

$items_html .= '<th width="5%;" align="center">#</th>';
$items_html .= '<th width="'.$item_width.'%" align="left">' . _l('estimate_table_item_heading') . '</th>';

foreach ($custom_fields_items as $cf) {
    $items_html .= '<th width="'.$headings_width.'%" align="left">' . $cf['name'] . '</th>';
}

$items_html .= '<th width="'.$headings_width.'%" align="right">' . $qty_heading . '</th>';
$items_html .= '<th width="'.$headings_width.'%" align="right">' . _l('estimate_table_rate_heading') . '</th>';

if (get_option('show_tax_per_item') == 1) {
    $items_html .= '<th width="'.$headings_width.'%" align="right">' . _l('estimate_table_tax_heading') . '</th>';
}

$items_html .= '<th width="'.$headings_width.'%" align="right">' . _l('estimate_table_amount_heading') . '</th>';
$items_html .= '</tr>';

$items_html .= '<tbody>';

$items_data = get_table_items_and_taxes($proposal->items,'proposal');

$taxes = $items_data['taxes'];
$items_html .= $items_data['html'];

$items_html .= '</tbody>';
$items_html .= '</table>';
//$items_html .= '<br /><br />';
$items_html .= '';
$items_html .= '<table cellpadding="6" style="font-size:'.($font_size+4).'px">';

$items_html .= '
<tr style="background-color:#f0f0f0;">
    <td align="right" width="84%"><strong>'._l('estimate_subtotal').'</strong></td>
    <td align="right" width="15%">' . format_money($proposal->subtotal,$proposal->symbol) . '</td>
</tr>';


if(is_sale_discount_applied($proposal)){
    $items_html .= '
    <tr style="background-color:#f0f0f0;">
        <td align="right" width="84%"><strong>' . _l('estimate_discount');
        if(is_sale_discount($proposal,'percent')){
            $items_html .= '(' . _format_number($proposal->discount_percent, true) . '%)';
        }
        $items_html .= '</strong>';
        $items_html .= '</td>';
        $items_html .= '<td align="right" width="15%">-' . format_money($proposal->discount_total, $proposal->symbol) . '</td>
    </tr>';
}

foreach ($taxes as $tax) {
    $items_html .= '<tr style="background-color:#f0f0f0;">
    <td align="right" width="84%"><strong>' . $tax['taxname'] . ' (' . _format_number($tax['taxrate']) . '%)' . '</strong></td>
    <td align="right" width="15%">' . format_money($tax['total_tax'], $proposal->symbol) . '</td>
</tr>';
}

if ((int)$proposal->adjustment != 0) {
    $items_html .= '<tr>
    <td align="right" width="84%"><strong>'._l('estimate_adjustment').'</strong></td>
    <td align="right" width="15%">' . format_money($proposal->adjustment,$proposal->symbol) . '</td>
</tr>';
}
$items_html .= '
<tr style="background-color:#f0f0f0;">
    <td align="right" width="84%"><strong>'._l('estimate_total').'</strong></td>
    <td align="right" width="15%">' . format_money($proposal->total, $proposal->symbol) . '</td>
</tr>';
$items_html .= '</table>';

if(get_option('total_to_words_enabled') == 1){
    $items_html .= '<br /><br /><br />';
    $items_html .= '<strong style="text-align:center;">'._l('num_word').': '.$CI->numberword->convert($proposal->total,$proposal->currency_name).'</strong>';
}

$proposal->content = str_replace('{proposal_items}', $items_html, $proposal->content);
//$proposal->content = str_replace('{field1}', $items_html, $proposal->content);


$timeframe_html = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="8">';

$timeframe_html .= '<tr height="20" bgcolor="#00c7fe" style="color:' . get_option('pdf_table_heading_text_color') . ';">';

$timeframe_html .= '<th width="15%;" align="center">Sr.No.</th>';
$timeframe_html .= '<th width="70%;" align="left">Phase</th>';


$timeframe_html .= '<th width="15%;" align="center">Week</th>';

$timeframe_html .= '</tr>';

$timeframe_html .= '<tbody>';

$timeframe_html .= '<tr>';

$timeframe_html .= '<td width="15%;" align="center">01 </td>';

$timeframe_html .= '<td width="70%;" align="left">Upon project sign-off well arrange a kick-off discovery meeting to learn more about your audience and goals. Well need access to your various analytics tools.</td>';

$timeframe_html .= '<td width="15%;" align="center">02</td>';

$timeframe_html .='</tr>';

$timeframe_html .= '<tr>';

$timeframe_html .= '<td width="15%;" align="center">02 </td>';

$timeframe_html .= '<td width="70%;" align="left">Well start pulling data and develop the strategy. This all-in- one document will include all of the information you need to move forward with a solid social media campaign.</td>';

$timeframe_html .= '<td width="15%;" align="center">05</td>';

$timeframe_html .='</tr>';

$timeframe_html .= '</tbody>';
$timeframe_html .= '</table>';





$first_page = '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><div><span style="font-size:30px; color:#ffffff;"><strong>Digital Marketing Proposal</strong></span></div>
    <div><span style="font-size:20px; color:#ffffff;"><strong>Client: </strong>'.$proposal->proposal_to.'</span></div>
    <div><span style="font-size:20px; color:#ffffff;"><strong>Submitted by: </strong>'.$login_full_name.' </span></div>
    <div><span style="font-size:20px; color:#ffffff;"><strong>Delivered on: </strong>'.$proposal->date.'</span></div> 
    <br/><br/><br/><br/><br/><br/>';



// Get the proposals css
// Theese lines should aways at the end of the document left side. Dont indent these lines
$html1 = <<<EOF
    $first_page

EOF;
$footer_html = '<div style="width:100% !important;height:10px;">Digital Marketing Proposal for 3D incredible pvt</div>';

$footer_logo_html = <<<EOF
    $footer_html

EOF;
/*<span style="font-size:20px; color:#ffffff;">Digital Marketing Proposal for '.$proposal->proposal_to.'</span><br />
    <span style="font-size:20px; color:#ffffff;">© Global Infocloud Pvt Ltd. </span>*/
    
/*$second_page = '<span style="font-size:20px; color:#ffffff; left:10px; text-align:justify;"> Dear '.$proposal->proposal_to.'</span>


    <p style="font-size:18px; text-align:justify; color:#373435">You need a social media strategy - don’t let anyone tell you any different. And we’re not talking about posting a cute cat video once a week on Facebook with the hope that your customers think you’re as adorable as said cat. You need to figure out where your target audience (you do know who that is, right?) is hanging out on the internet - is it Twitter, Facebook, Tinder, Google+, Pinterest, Instagram, or some other online nook you’re not yet aware of?</p> 
    <p style="font-size:18px; text-align:justify; color:#373435">We also need to make sure your brand message is one that will resonate with your target market on social media. What information are they looking for? What problem can you solve for them? And where do they want to find the solution? Finally, we’ll uncover which social media channels are the best match for your website’s conversion process. The real goal is to get a consistent traffic of buyers headed to your site so we want to make certain that we’re catching your target at time when they’re likely to buy and make it easy for them to do so, wherever they are.</p> 
    <p style="font-size:18px; text-align:justify; color:#373435">Based on our experience, we are highly confident we can meet or exceed your search traffic within the next <strong>'.$Search_Traffic_within_1.'</strong> months. To start with, well need to perform a SEO audit. As discussed, your goal is to work with <strong>'.$Goal_is_to_work_with_2.'</strong> new customers within one year (average of <strong>'.$custField_3.'</strong> /month). At your current close rate of 35%, you’ll need to generate approximately <strong>'.$Generate_Approx_Qty_4.'</strong> high quality leads per month, meaning that they reach the bottom of the funnel and become a sales qualified lead. If a “guesstimated” '.$Guesstimated_Leads_5.'% of your leads move all the way through the buyer funnel, you’ll need to generate <strong>'.$Need_to_Generate_6.'</strong> top or middle of the funnel leads. Your current visitor to lead rate is ____%, but your goal is closer to the___% rate. At ____%, you’ll need to increase your traffic from <strong>'.$Increas_Trafic_From_10.'</strong> visits/mo to <strong>'.$Increase_Traffic_To_11.'</strong> visits/mo to make the process viable.</p> 
    <p style="font-size:18px; color:#373435; text-align:justify;">As the following pages of my proposal will show, we utilize a wide range of skills to accomplish this boost in qualified web traffic. If you have any questions or concerns about this proposal, please don’t hesitate to leave a comment or email me at <span style="color:#ffffff;">'.$login_email.'</span>.</p><br /><span style="font-size:20px; color:#ffffff;">Best,</span><br />
    <span style="font-size:20px; color:#ffffff;">'.$login_full_name.'</span><br />
    <span style="font-size:20px; color:#ffffff;">'.get_option('invoice_company_name').'</span><br />
    <br /><br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></p>
    ';*/
    $second_page = '<span style="font-size:20px; left:10px; text-align:justify; color:#373435;"> Dear '.$proposal->proposal_to.',</span>


    <p style="font-size:18px; text-align:justify; color:#727376">You need a social media strategy - don’t let anyone tell you any different. And we’re not talking about posting a cute cat video once a week on Facebook with the hope that your customers think you’re as adorable as said cat. You need to figure out where your target audience (you do know who that is, right?) is hanging out on the internet - is it Twitter, Facebook, Tinder, Google+, Pinterest, Instagram, or some other online nook you’re not yet aware of?</p> 
    <p style="font-size:18px; text-align:justify; color:#727376">We also need to make sure your brand message is one that will resonate with your target market on social media. What information are they looking for? What problem can you solve for them? And where do they want to find the solution? Finally, we’ll uncover which social media channels are the best match for your website’s conversion process. The real goal is to get a consistent traffic of buyers headed to your site so we want to make certain that we’re catching your target at time when they’re likely to buy and make it easy for them to do so, wherever they are.</p>
    <p style="font-size:18px; color:#727376;">'.html_entity_decode($proposal->mycal_feild).'</p>
    <p style="font-size:18px; color:#727376; text-align:justify;">As the following pages of my proposal will show, we utilize a wide range of skills to accomplish this boost in qualified web traffic. If you have any questions or concerns about this proposal, please don’t hesitate to leave a comment or email me at <span>'.$login_email.'</span>.</p><br /><span style="font-size:20px; color:#373435;">Best,</span><br />
    <span style="font-size:20px; color:#373435;">'.$login_full_name.'</span><br />
    <span style="font-size:20px; color:#373435;">'.get_option('invoice_company_name').'</span><br />
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></p>
    ';

$html2 = <<<EOF

    $second_page

EOF;

$third_page = ' <p style="font-size:18px; color:#727376; text-align:justify;">Without getting too deep here, the internet, like the universe, is constantly expanding. There are new tools, new sites, new platforms, new rules, and new galaxies being discovered all the time. But before you get overwhelmed thinking your site is going to get sucked into the black hole of online oblivion, we can help. By developing a social media strategy that is active instead of reactive, strategic instead of tactical, we’ll put down some objectives and metrics that will make your website perform as if the Force is with you.</p>

    <p><span style="font-size:22px; text-align:justify; color:#373435;">Step 1: Everybody’s a Somebody When it Comes to Social Media.</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Social media and its eŨects impacts all areas of your company - sales/marketing, production, customer service, legal, etc. That’s why no one person should be crowned king of social media in your company. To be really eŨective you need to be more representative - create a team from the various departments of your company to maximize their experience and perspective on your social media strategy. This will also make sure everyone knows what’s going on when it comes to your online activity.
        </span></p>
    <p><span style="font-size:22px; text-align:justify; color:#373435;">Step 2: There’s a Time to Shut Up and Listen</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">And that time is now. Being a good listener is an invaluable tool and never more so than in social media. You can learn a lot by listening to what your customers are saying - not just about your brand but about your competitors, and other things they value or are turned oŨ by. And speaking of competitors, it’s also important to listen to what messages they’re pumping out and where they’re turning up.
        </span></p>
        <p><span style="font-size:22px; text-align:justify; color:#373435;">Step 3: Stay Focused</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">You can’t be all things to all people nor can you accomplish all your goals in one campaign. You’ll be far more eŨective if you choose one goal, maybe two, TOPS, for your social media strategy. Do that, do it well. Then move on to slay the next dragon.
        </span></p>
        <p><span style="font-size:22px; text-align:justify; color:#373435;">Step 4: Are We There Yet?</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">To figure out how successful we’ve been with our social media strategy, we need to deŪne what success really means to your business. Is it more sales? Is it sign-ups? Downloads? Likes? How will you measure return on investment?
        </span></p>
        <p><span style="font-size:22px; text-align:justify; color:#373435;">Step 5: Know The Customer</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">We need to know who your target audience is if we’re going to find them online. It’s crucial to understand their demographic, their wants, needs, challenges, and interests. Then we can craft a message they’re going to hear loud and clear in a place where they want to hear it.
        </span></p>
        <p><span style="font-size:22px; text-align:justify; color:#373435;">Step 6: People First</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Social media is just that, social. That means people interacting with other people, so to be successful you need to be sure your company is acting like a person, not a corporate entity. It’s important to start thinking about how you can express the human elements of your brand even if you’re in the B2B market because believe it or not, they’re people too.
        </span></p>
        <p><span style="font-size:22px; text-align:justify; color:#373435;">Step 7: Create a Channel Plan</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Once we understand your audience and your message, we need to create a tactical plan for how, where and when we’re going to reach out to on social media, whether it’s Facebook, Twitter, etc.

        </span><br /><br /><br /><br /><br /><br /><br /><br /></p>';

$html3 = <<<EOP

        $third_page

EOP;


$fourth_page = ' <p style="font-size:18px; text-align:justify; color:#727376;"> Search Engine Optimization starts with knowing where you’re currently at. Only then can you determine where you want to be. Our 6-step SEO audit allows us to perform in-depth research on your existing site and provide specific recommendations to improve your rankings. After the audit, we’ll propose a plan tailored to your needs that will show how we’ll get you the final results. Here’s how we’ll approach the audit:</p>
    <p><span style="font-size:22px; text-align:justify; color:#373435;">1. Getting the lay of the land </span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">The first thing we do when auditing your website for SEO is configure our crawling tools and start collecting data associated with your site to learn where you currently rank. We’ll review your Google Analytics and review your traffic patterns and consult Google Webmaster Tools free diagnostic tools. Having all of this data at our disposal we’re ready to begin the audit.
        </span></p>
        <p><span style="font-size:22px; text-align:justify; color:#373435;">2. Auditing Accessibility</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">This part of the audit looks at how search engines are currently able to access your website. We want to make sure the basics are there and nothing is impeding the engines from crawling the pages. We’ll review the following:
        <ul>
        <li>Robots.txt</li>
        <li>Robots meta tag</li>
        <li>HTTP status codes</li>
        <li>XML sitemaps</li>
        <li>Site architecture</li>
        <li>Flash or Javascript navigation</li>
        </ul>
        </span>
        <span style="font-size:22px; text-align:justify; color:#373435;">SITE PERFORMANCE </span><br>
        <span style="font-size:18px; text-align:justify; color:#727376;">We realize you may not be familiar with some of these terms. If you have any questions about some of what we’ll be reviewing, please let us know and we’ll be happy to explain in simple terms! </span></p>

        <p><span style="font-size:22px; text-align:justify; color:#373435;">3. Indexability</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Once we determine that search engines can access your pages, next we want to make sure they’re actually indexing them (including them in results). We do this quite simply by running search queries through Google, Yahoo! and Bing, and learning how many pages on your website are being indexed. We’ll run brand searches along with basic keywords (industry, product, location) and recording the results. In the event you’re being blocked we’ll dig to learn why and then propose a solution for fixing any errors to get your site back in the rankings.
        </span></p>

        <p><span style="font-size:22px; text-align:justify; color:#373435;">4. On-page ranking factors</span><br />
        <span style="font-size:18px; text-align:justify; color:#727376;">Now that we’ve determined that your site is being indexed and crawled, we’ll review more about what factors on your site inżuence those rankings. We’ll look at the following items:
            <ul>
            <li>URLs</li>
            <li>Since a URL is the entry point to a page’s content, it’s a logical place to begin ouron-page analysis.</li>
            <li>URL-based duplicate content</li>
            </ul>
            URLs are often responsible for the majority of duplicate content on a website because every URL represents a unique entry point into the site. If two distinct URLs point to the same page (without the use of redirection), search engines believe two distinct pages exist.
        </span><br >
        </p>';

$html4 = <<<EOP
        $fourth_page
EOP;

$fifth_page = '<p><span style="font-size:22px; text-align:justify; color:#373435;">CONTENT  </span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Content is the most important thing Google and other search engines look for when determining how to rank your site. We’ll analyze your content to determine whether your content is valuable to it’s audience and how targeted they keywords are, and make sure it’s not spammy or difficult to read.
        </span></p>

       <p><span style="font-size:22px; text-align:justify; color:#373435;">INFORMATION ARCHITECTURE </span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Information architecture defines how information is laid out on the site. It is the blueprint for how your site presents information (and how you expect visitors to consume that information). During the audit, we’ll ensure that each of your site’s pages has a purpose. We’ll also verify that each of your targeted keywords is being represented by a page on your site.
        </span></p>

       <p><span style="font-size:22px; text-align:justify; color:#373435;">KEYWORD CANNIBALISM </span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Keyword cannibalism describes the situation where your site has multiple pages that target the same keyword. When multiple pages target a keyword, it creates confusion for the search engines, and more importantly, it creates confusion for visitors.
        </span></p>
        
       <p><span style="font-size:22px; text-align:justify; color:#373435;">DUPLICATE CONTENT </span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">Your site has duplicate content if multiple pages contain the same (or nearly the same) content. Unfortunately, these pages can be both internal and external (i.e., hosted on a different domain).
        </span></p>

        <p><span style="font-size:22px; text-align:justify; color:#373435;">HTML MARKUP</span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">The markup in the source code of your pages is extremely important for how pages get crawled. We’ll focus on the title tags and meta descriptions, while also paying attention to headings and images.
        </span></p>

        <p><span style="font-size:22px; text-align:justify; color:#373435;">OUTLINKS </span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">When one page links to another, that link is an endorsement of the receiving page’s quality. Thus, an important part of the audit is making sure your site links to other high quality sites.
        </span></p>
    <p><span style="font-size:22px; text-align:justify; color:#373435;">5. Off-page ranking factors</span><br />
        <span style="font-size:18px; text-align:justify; color:#727376;">While what’s on your website is important for SEO, what’s on the website is just as important. Your site’s <br>quality is largely determined by the quality of the sites linking to it. Thus, it is extremely important to analyze the backlink profile of your site and identify opportunities for improvement. We’ll review it from many angles, including:
            <ul>
            
            <li>How popular your website currently is compared to the competition?</li>
            <li>Are you getting backlinks from popular websites?</li>
            <li>Are you gaining or losing popularity over time?</li>
            <li>Is your website trustworthy?</li>
            <li>How many domains link to you?</li>
            <li>What is the Page Authority and Domain Authority?</li>
            <li>How is your social engagement?</li>
            </ul>
            <span style="font-size:22px; text-align:justify; color:#373435;">6. Competitive Analysis </span><br />
    <span style="font-size:18px; text-align:justify; color:#727376;">After we’ve reviewed your site in detail, we’ll also compare it against 3 competitors in all of the ways listed above, and compile the data down into actionable items.
        </span> <br /><br /><br /><br /><br /><br /></p>';


$html5 = <<<EOP
        $fifth_page
EOP;



$last_page = '<p><span style="font-size:22px; text-align:justify; color:#ffffff;"><strong>OUTLINKS </strong> </span><br />
    <span style="font-size:18px; text-align:justify; color:#373435;">When one page links to another, that link is an endorsement of the receiving page’s quality. Thus, an important part of the audit is making sure your site links to other high quality sites.
        </span></p>';

$html_last = <<<EOP
        $last_page
EOP;

$eight_page = '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br />
<br />
';

$html8 = <<<EOP
        $eight_page
EOP;

$sixth_page = '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br />
';

$html6 = <<<EOP
        $sixth_page
EOP;

$seventh_page = '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
';

$html7 = <<<EOP
        $seventh_page
EOP;



$html11 = <<<EOF
<br /><br /><br />
<p style="font-size:20px;"># $number
<br /><span style="font-size:15px;">$proposal->subject</span>
</p>
$proposal_date
<br />
$open_till
<div style="width:1200px !important;">
$proposal->content
</div>

EOF;

/* First Page Start */ 
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/1.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();

$pdf->writeHTML($html1, true, false, true, false, '');
//$pdf->writeHTMLCell(21, '', 0, 29.7 - 4, $footer_logo_html, 0, 1, false, true, 'L', false);

/* First Page End */ 

/* Second Page Start */ 
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/2.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();

$pdf->writeHTML($html2, true, false, true, false, '');
/* END Second Page */


/* Third Page Start */ 

$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/3.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();

$pdf->writeHTML($html3, true, false, true, false, '');
/* END Third Page */


/* Fourth Page Start */ 
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/4.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// restore auto-page-break status

$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content

$pdf->setPageMark();
$pdf->writeHTML($html4, true, false, true, false, '');
/* END Fourth Page */


/* Fifth Page Start */ 
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/5.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();

$pdf->writeHTML($html5, true, false, true, false, '');
/* END Fifth Page */


/* Start Sixth Page */
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/6.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();

$pdf->writeHTML($html6, true, false, true, false, '');
/* END Sixth Page */


/* Start Seventh Page */
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/7.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();

$pdf->writeHTML($html7, true, false, true, false, '');
/* END Seventh Page */


/* Start Eight Page */
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/proposal/8.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();

$pdf->writeHTML($html8, true, false, true, false, '');
/* END eight Page */


/* last Page Start */ 
$pdf->setPrintHeader(false);

// get the current page break margin
$bMargin = $pdf->getBreakMargin();

// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();

// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);

// set bacground image
$img_file = base_url().'uploads/last_page.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

// set the starting point for the page content
$pdf->setPageMark();


//$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $pdf_logo_url, 0, 1, false, true, 'L', true);

$pdf->writeHTMLCell(($swap == '0' ? (($dimensions['wk'] / 2) - $dimensions['rm']) : ''), '', '', ($swap == '0' ? $y : ''), $proposal_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);

$pdf->writeHTMLCell(($dimensions['wk'] / 2) - $dimensions['lm'], $rowcount*7, '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);

$pdf->writeHTML($html11, true, false, true, false, '');

