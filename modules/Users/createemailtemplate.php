<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
    
  <title> Email Template: New Template </title>
</head>
<body>
<form method="post" action="index.php?module=Users&action=saveemailtemplate">  
<TABLE WIDTH="100%" CELLPADDING=0 CELLSPACING=0 BORDER=0>
<TR><TD>
    <TABLE WIDTH="100%" CELLPADDING=0 CELLSPACING=0 BORDER=0>

    <TR>
    <TD ALIGN=LEFT CLASS="moduleTitle hline" NOWRAP> Email Template: New Template</TD>
    </TR>
    </TABLE>
</TD></TR>
</table>

            <TABLE WIDTH="100%" CELLPADDING="0" CELLSPACING="2" BORDER="0">
              <TR VALIGN="TOP">
                <TD>Use merge fields to personalize your email content. You can add substitute text to any merge field.</TD>
		</TR>
	</TABLE>
<br>	
<TABLE WIDTH="90%" CELLPADDING="0" CELLSPACING="5" BORDER="0">
                <TD NOWRAP>&nbsp;<input type="submit" name="save" value=" Save " class="button" >&nbsp;<input type="submit" name="cancel" value="Cancel" class="button" ></TD>
		<TD ALIGN='right'><font class='required'>*</font>Indicates Required Field</TD>
              </TR>
	</TABLE> 
<SCRIPT>
    var allOptions = null;
    function setAllOptions(inputOptions) {
        allOptions = inputOptions;
    }

    function modifyMergeFieldSelect(cause, effect) {
        var selected = cause.options[cause.selectedIndex].value; 
        var s = allOptions[cause.selectedIndex];
            
        effect.length = s;
        for (var i = 0; i < s; i++) {
            effect.options[i] = s[i];
        }
        document.getElementById('mergeFieldValue').value = '';
    }

    function init() {
        var blankOption = new Option('', '');
        var allOpts = new Object(0);
        var options = null;
        
            
            options = new Object(45);
            options[0] = blankOption;
            
                
                options[1] = new Option('Account ID', '{!Account_ID}'); 
            
                
                options[2] = new Option('Account: Name', '{!Account_Name}'); 
            
                
                options[3] = new Option('Account: Site', '{!Account_Site}'); 
            
                
                options[4] = new Option('Account: Type', '{!Account_Type}'); 
            
                
                options[5] = new Option('Account: Industry', '{!Account_Industry}'); 
            
                
                options[6] = new Option('Account: Billing Address', '{!Account_BillingAddress}'); 
            
                
                options[7] = new Option('Account: Billing City', '{!Account_BillingCity}'); 
            
                
                options[8] = new Option('Account: Billing State/Province', '{!Account_BillingState}'); 
            
                
                options[9] = new Option('Account: Billing Postal Code', '{!Account_BillingPostalCode}'); 
            
                
                options[10] = new Option('Account: Billing Country', '{!Account_BillingCountry}'); 
            
                
                options[11] = new Option('Account: Full Billing Address', '{!Account_FullBillingAddress}'); 
            
                
                options[12] = new Option('Account: Shipping Address', '{!Account_ShippingAddress}'); 
            
                
                options[13] = new Option('Account: Shipping City', '{!Account_ShippingCity}'); 
            
                
                options[14] = new Option('Account: Shipping State/Province', '{!Account_ShippingState}'); 
            
                
                options[15] = new Option('Account: Shipping Postal Code', '{!Account_ShippingPostalCode}'); 
            
                
                options[16] = new Option('Account: Shipping Country', '{!Account_ShippingCountry}'); 
            
                
                options[17] = new Option('Account: Full Shipping Address', '{!Account_FullShippingAddress}'); 
            
                
                options[18] = new Option('Account: Phone', '{!Account_Phone}'); 
            
                
                options[19] = new Option('Account: Fax', '{!Account_Fax}'); 
            
                
                options[20] = new Option('Account: Website', '{!Account_Website}'); 
            
                
                options[21] = new Option('Account Number', '{!Account_AccountNumber}'); 
            
                
                options[22] = new Option('Account: Rating', '{!Account_Rating}'); 
            
                
                options[23] = new Option('Account: Annual Revenue', '{!Account_AnnualRevenue}'); 
            
                
                options[24] = new Option('Account: Employees', '{!Account_Employees}'); 
            
                
                options[25] = new Option('Account Ownership', '{!Account_Ownership}'); 
            
                
                options[26] = new Option('Account: SICCode', '{!Account_SICCode}'); 
            
                
                options[27] = new Option('Account: Ticker Symbol', '{!Account_TickerSymbol}'); 
            
                
                options[28] = new Option('Account: Description', '{!Account_Description}'); 
            
                
                options[29] = new Option('Account: Last Modified', '{!Account_LastUpdated}'); 
            
                
                options[30] = new Option('Account Owner: First Name', '{!AccountOwner_FirstName}'); 
            
                
                options[31] = new Option('Account Owner: Last Name', '{!AccountOwner_LastName}'); 
            
                
                options[32] = new Option('Account Owner: Full Name', '{!AccountOwner_FullName}'); 
            
                
                options[33] = new Option('Account Owner: Title', '{!AccountOwner_Title}'); 
            
                
                options[34] = new Option('Account Owner: Phone', '{!AccountOwner_Phone}'); 
            
                
                options[35] = new Option('Account Owner: Email', '{!AccountOwner_Email}'); 
            
                
                options[36] = new Option('Account: Detail Link', '{!Account_Link}'); 
            
                
                options[37] = new Option('SLA Expiration Date on Account', '{!Account_SLA_Expiration_Date}'); 
            
                
                options[38] = new Option('Upsell Opportunity on Account', '{!Account_Upsell_Opportunity}'); 
            
                
                options[39] = new Option('SLA on Account', '{!Account_SLA}'); 
            
                
                options[40] = new Option('SLA Serial Number on Account', '{!Account_SLA_Serial_Number}'); 
            
                
                options[41] = new Option('Number of Locations on Account', '{!Account_Number_of_Locations}'); 
            
                
                options[42] = new Option('Active on Account', '{!Account_Active}'); 
            
                
                options[43] = new Option('Customer Priority on Account', '{!Account_Customer_Priority}'); 
            
                
                options[44] = new Option('ShankarValue on Account', '{!Account_ShankarValue}'); 
            
            allOpts[0] = options;
        
            
            options = new Object(44);
            options[0] = blankOption;
            
                
                options[1] = new Option('Contact ID', '{!Contact_ID}'); 
            
                
                options[2] = new Option('Contact: First Name', '{$contact_Contact_FirstName}'); 
            
                
                options[3] = new Option('Contact: Last Name', '{!Contact_LastName}'); 
            
                
                options[4] = new Option('Contact: Full Name', '{!Contact_FullName}'); 
            
                
                options[5] = new Option('Contact: Salutation', '{!Contact_Salutation}'); 
            
                
                options[6] = new Option('Contact: Title', '{!Contact_Title}'); 
            
                
                options[7] = new Option('Contact: Birthdate', '{!Contact_Birthdate}'); 
            
                
                options[8] = new Option('Contact: Department', '{!Contact_Department}'); 
            
                
                options[9] = new Option('Contact: Lead Source', '{!Contact_LeadSource}'); 
            
                
                options[10] = new Option('Contact: Mailing Address', '{!Contact_MailingAddress}'); 
            
                
                options[11] = new Option('Contact: Mailing City', '{!Contact_MailingCity}'); 
            
                
                options[12] = new Option('Contact: Mailing State/Province', '{!Contact_MailingState}'); 
            
                
                options[13] = new Option('Contact: Mailing Postal Code', '{!Contact_MailingPostalCode}'); 
            
                
                options[14] = new Option('Contact: Mailing Country', '{!Contact_MailingCountry}'); 
            
                
                options[15] = new Option('Contact: Full Mailing Address', '{!Contact_FullMailingAddress}'); 
            
                
                options[16] = new Option('Contact: Other Address', '{!Contact_OtherAddress}'); 
            
                
                options[17] = new Option('Contact: OtherCity', '{!Contact_OtherCity}'); 
            
                
                options[18] = new Option('Contact: Other State/Province', '{!Contact_OtherState}'); 
            
                
                options[19] = new Option('Contact: Other Postal Code', '{!Contact_OtherPostalCode}'); 
            
                
                options[20] = new Option('Contact: Other Country', '{!Contact_OtherCountry}'); 
            
                
                options[21] = new Option('Contact: Full Other Address', '{!Contact_FullOtherAddress}'); 
            
                
                options[22] = new Option('Contact: Phone', '{!Contact_Phone}'); 
            
                
                options[23] = new Option('Contact: Fax', '{!Contact_Fax}'); 
            
                
                options[24] = new Option('Contact: Mobile', '{!Contact_Mobile}'); 
            
                
                options[25] = new Option('Contact: Home Phone', '{!Contact_HomePhone}'); 
            
                
                options[26] = new Option('Contact: Other Phone', '{!Contact_OtherPhone}'); 
            
                
                options[27] = new Option('Contact: Email', '{!Contact_Email}'); 
            
                
                options[28] = new Option('Contact: Assistant', '{!Contact_Assistant}'); 
            
                
                options[29] = new Option('Contact: Assistant Phone', '{!Contact_AsstPhone}'); 
            
                
                options[30] = new Option('Contact: Description', '{!Contact_Description}'); 
            
                
                options[31] = new Option('Contact: Last Stay-in-Touch Request Date', '{!Contact_Stay-in-Touch_Request_Date}'); 
            
                
                options[32] = new Option('Contact: Last Stay-in-Touch Save Date', '{!Contact_Stay-in-Touch_Save_Date}'); 
            
                
                options[33] = new Option('Contact: Last Modified', '{!Contact_LastUpdated}'); 
            
                
                options[34] = new Option('Contact Owner: First Name', '{!ContactOwner_FirstName}'); 
            
                
                options[35] = new Option('Contact Owner: Last Name', '{!ContactOwner_LastName}'); 
            
                
                options[36] = new Option('Contact Owner: Full Name', '{!ContactOwner_FullName}'); 
            
                
                options[37] = new Option('Contact Owner: Title', '{!ContactOwner_Title}'); 
            
                
                options[38] = new Option('Contact Owner: Phone', '{!ContactOwner_Phone}'); 
            
                
                options[39] = new Option('Contact Owner: Email', '{!ContactOwner_Email}'); 
            
                
                options[40] = new Option('Contact: Detail Link', '{!Contact_Link}'); 
            
                
                options[41] = new Option('Languages on Contact', '{!Contact_Languages}'); 
            
                
                options[42] = new Option('Level on Contact', '{!Contact_Level}'); 
            
                
                options[43] = new Option('ShankarCurrency on Contact', '{!Contact_ShankarCurrency}'); 
            
            allOpts[1] = options;
        
            
            options = new Object(52);
            options[0] = blankOption;
            
                
                options[1] = new Option('Lead ID', '{!Lead_ID}'); 
            
                
                options[2] = new Option('Lead: First Name', '{!Lead_FirstName}'); 
            
                
                options[3] = new Option('Lead: Last Name', '{!Lead_LastName}'); 
            
                
                options[4] = new Option('Lead: Full Name', '{!Lead_FullName}'); 
            
                
                options[5] = new Option('Lead: Salutation', '{!Lead_Salutation}'); 
            
                
                options[6] = new Option('Lead: Title', '{!Lead_Title}'); 
            
                
                options[7] = new Option('Lead: Company', '{!Lead_Company}'); 
            
                
                options[8] = new Option('Lead: Lead Source', '{!Lead_LeadSource}'); 
            
                
                options[9] = new Option('Lead: Full Address', '{!Lead_FullAddress}'); 
            
                
                options[10] = new Option('Lead: Address', '{!Lead_Address}'); 
            
                
                options[11] = new Option('Lead: City', '{!Lead_City}'); 
            
                
                options[12] = new Option('Lead: State/Province', '{!Lead_State}'); 
            
                
                options[13] = new Option('Lead: Postal Code', '{!Lead_PostalCode}'); 
            
                
                options[14] = new Option('Lead: Country', '{!Lead_Country}'); 
            
                
                options[15] = new Option('Lead: Status', '{!Lead_Status}'); 
            
                
                options[16] = new Option('Lead: Rating', '{!Lead_Rating}'); 
            
                
                options[17] = new Option('Lead: Industry', '{!Lead_Industry}'); 
            
                
                options[18] = new Option('Lead: Annual Revenue', '{!Lead_AnnualRevenue}'); 
            
                
                options[19] = new Option('Lead: Employees', '{!Lead_Employees}'); 
            
                
                options[20] = new Option('Lead: Phone', '{!Lead_Phone}'); 
            
                
                options[21] = new Option('Lead: Mobile', '{!Lead_Mobile}'); 
            
                
                options[22] = new Option('Lead: Fax', '{!Lead_Fax}'); 
            
                
                options[23] = new Option('Lead: Email', '{!Lead_Email}'); 
            
                
                options[24] = new Option('Lead: Website', '{!Lead_Website}'); 
            
                
                options[25] = new Option('Lead: Description', '{!Lead_Description}'); 
            
                
                options[26] = new Option('Lead: Detail Link', '{!Lead_Link}'); 
            
                
                options[27] = new Option('Lead: Created Date', '{!Lead_CreatedDate}'); 
            
                
                options[28] = new Option('Lead: Last Modified', '{!Lead_LastUpdated}'); 
            
                
                options[29] = new Option('Lead Owner: First Name', '{!LeadOwner_FirstName}'); 
            
                
                options[30] = new Option('Lead Owner: Last Name', '{!LeadOwner_LastName}'); 
            
                
                options[31] = new Option('Lead Owner: Full Name', '{!LeadOwner_FullName}'); 
            
                
                options[32] = new Option('Lead Owner: Title', '{!LeadOwner_Title}'); 
            
                
                options[33] = new Option('Lead Owner: Phone', '{!LeadOwner_Phone}'); 
            
                
                options[34] = new Option('Lead Owner: Email', '{!LeadOwner_Email}'); 
            
                
                options[35] = new Option('Current Generator(s) on Lead', '{!Lead_Current_Generator(s)}'); 
            
                
                options[36] = new Option('SIC Code on Lead', '{!Lead_SIC_Code}'); 
            
                
                options[37] = new Option('Number of Locations on Lead', '{!Lead_Number_of_Locations}'); 
            
                
                options[38] = new Option('Product Interest on Lead', '{!Lead_Product_Interest}'); 
            
                
                options[39] = new Option('Primary on Lead', '{!Lead_Primary}'); 
            
                
                options[40] = new Option('mytext on Lead', '{!Lead_mytext}'); 
            
                
                options[41] = new Option('comments on Lead', '{!Lead_comments}'); 
            
                
                options[42] = new Option('test1 on Lead', '{!Lead_test1}'); 
            
                
                options[43] = new Option('IsMarried on Lead', '{!Lead_IsMarried}'); 
            
                
                options[44] = new Option('shankar on Lead', '{!Lead_shankar}'); 
            
                
                options[45] = new Option('Services on Lead', '{!Lead_Services}'); 
            
                
                options[46] = new Option('TestNumber on Lead', '{!Lead_TestNumber}'); 
            
                
                options[47] = new Option('shankardate on Lead', '{!Lead_shankardate}'); 
            
                
                options[48] = new Option('SNumber on Lead', '{!Lead_SNumber}'); 
            
                
                options[49] = new Option('SDateTime on Lead', '{!Lead_SDateTime}'); 
            
                
                options[50] = new Option('SCurrency on Lead', '{!Lead_SCurrency}'); 
            
                
                options[51] = new Option('No. of Currencies on Lead', '{!Lead_No._of_Currencies}'); 
            
            allOpts[2] = options;
        
            
            options = new Object(28);
            options[0] = blankOption;
            
                
                options[1] = new Option('Opportunity ID', '{!Opportunity_ID}'); 
            
                
                options[2] = new Option('Opportunity: Name', '{!Opportunity_Name}'); 
            
                
                options[3] = new Option('Opportunity Type', '{!Opportunity_Type}'); 
            
                
                options[4] = new Option('Opportunity: Lead Source', '{!Opportunity_LeadSource}'); 
            
                
                options[5] = new Option('Opportunity Amount', '{!Opportunity_Amount}'); 
            
                
                options[6] = new Option('Opportunity: Close Date', '{!Opportunity_CloseDate}'); 
            
                
                options[7] = new Option('Opportunity: Next Step', '{!Opportunity_NextStep}'); 
            
                
                options[8] = new Option('Opportunity: Stage', '{!Opportunity_Stage}'); 
            
                
                options[9] = new Option('Opportunity: Probability', '{!Opportunity_Probability}'); 
            
                
                options[10] = new Option('Opportunity: Created Date', '{!Opportunity_CreatedDate}'); 
            
                
                options[11] = new Option('Opportunity: Description', '{!Opportunity_Description}'); 
            
                
                options[12] = new Option('Opportunity: Last Modified', '{!Opportunity_LastUpdated}'); 
            
                
                options[13] = new Option('Opportunity Owner: First Name', '{!OpportunityOwner_FirstName}'); 
            
                
                options[14] = new Option('Opportunity Owner: Last Name', '{!OpportunityOwner_LastName}'); 
            
                
                options[15] = new Option('Opportunity Owner: Full Name', '{!OpportunityOwner_FullName}'); 
            
                
                options[16] = new Option('Opportunity Owner: Title', '{!OpportunityOwner_Title}'); 
            
                
                options[17] = new Option('Opportunity Owner: Phone', '{!OpportunityOwner_Phone}'); 
            
                
                options[18] = new Option('Opportunity Owner: Email', '{!OpportunityOwner_Email}'); 
            
                
                options[19] = new Option('Opportunity: Account ID', '{!Opportunity_Account_ID}'); 
            
                
                options[20] = new Option('Opportunity: Account Name', '{!Opportunity_Account_Name}'); 
            
                
                options[21] = new Option('Opportunity: Campaign Source', '{!Opportunity_Campaign_Source}'); 
            
                
                options[22] = new Option('Opportunity: Detail Link', '{!Opportunity_Link}'); 
            
                
                options[23] = new Option('Tracking Number on Opportunity', '{!Opportunity_Tracking_Number}'); 
            
                
                options[24] = new Option('Order Number on Opportunity', '{!Opportunity_Order_Number}'); 
            
                
                options[25] = new Option('Main Competitor(s) on Opportunity', '{!Opportunity_Main_Competitor(s)}'); 
            
                
                options[26] = new Option('Current Generator(s) on Opportunity', '{!Opportunity_Current_Generator(s)}'); 
            
                
                options[27] = new Option('Delivery/Installation Status on Opportunity', '{!Opportunity_Delivery/Installation_Status}'); 
            
            allOpts[3] = options;
        
            
            options = new Object(11);
            options[0] = blankOption;
            
                
                options[1] = new Option('Opportunity Line Item ID', '{!Opportunity_LineItem_ID}'); 
            
                
                options[2] = new Option('Opportunity Line Item: Product Name', '{!Opportunity_LineItem_ProductName}'); 
            
                
                options[3] = new Option('Opportunity Line Item: Product Code', '{!Opportunity_LineItem_ProductCode}'); 
            
                
                options[4] = new Option('Opportunity Line Item: Quantity', '{!Opportunity_LineItem_Quantity}'); 
            
                
                options[5] = new Option('Opportunity Line Item: Total Price', '{!Opportunity_LineItem_TotalPrice}'); 
            
                
                options[6] = new Option('Opportunity Line Item: Sales Price', '{!Opportunity_LineItem_UnitPrice}'); 
            
                
                options[7] = new Option('Opportunity Line Item: Date', '{!Opportunity_LineItem_Date}'); 
            
                
                options[8] = new Option('Opportunity Line Item: Description', '{!Opportunity_LineItem_Description}'); 
            
                
                options[9] = new Option('Opportunity Line Item: Default Unit Price', '{!Opportunity_LineItem_DefaultUnitPrice}'); 
            
                
                options[10] = new Option('Opportunity Line Item: Product Description', '{!Opportunity_LineItem_ProductDescription}'); 
            
            allOpts[4] = options;
        
        
        setAllOptions(allOpts);
    }

    init();

</SCRIPT>

    <TABLE WIDTH="90%" BORDER=0 CELLSPACING=0 CELLPADDING=1 class="formOuterBorder">
        <TR>
            <TD class="formSecHeader">
            Available Merge Fields
            </TD>
        </TR>
        <TR>

            <TD>

            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1>

                <TR>
                    <TD style="font-family:arial;font-size=8pt;">Select Field Type</TD>
                    <TD style="font-family:arial;font-size=8pt;">Select Field</TD>
                    <TD style="font-family:arial;font-size=8pt;">Copy Merge Field Value</TD>
                <tr>

                    <TD>
                        <SELECT id="entityType" ONCHANGE="modifyMergeFieldSelect(this, document.getElementById('mergeFieldSelect'));">
                            
                                <OPTION VALUE="0" >Account Fields
                            
                                <OPTION VALUE="1" SELECTED>Contact Fields
                            
                                <OPTION VALUE="2" >Lead Fields
                            
                                <OPTION VALUE="3" >Opportunity Fields
                                                        
                                                         
                        </SELECT>

                    </TD>
                    <TD>
                        <SELECT id="mergeFieldSelect" onchange="document.getElementById('mergeFieldValue').value=this.options[this.selectedIndex].value;"></SELECT>
                    </TD>
                    <TD>
                        <INPUT TYPE=TEXT id="mergeFieldValue" style="width:140"; VALUE="" SIZE=40>
                    </TD>
                </TR>
                
                    <TR>

                        <TD colspan=3>Copy and paste the merge field value into your template below.</TD>
                    </TR>
                
            </TABLE>
            <SCRIPT>
                modifyMergeFieldSelect(document.getElementById('mergeTypeSelect'), document.getElementById('mergeFieldSelect'));
            </SCRIPT>
        </TD></TR>
    </TABLE>
<br>
<table width="90%" border=0 cellspacing=1 cellpadding=0 class="formOuterBorder">
<td class="formSecHeader" colspan=4 nowrap>Email Template Information:</td>
<tr><td nowrap class="dataLabel"><font class="required">*</font>Folder:
</td><td colspan=4><select name="foldername" tabindex="1"><option value="Personal">Personal</option>
<option value="Public" selected>Public</option>
</select></td></tr>
<tr ><td nowrap class="dataLabel"><font class="required">*</font>Template Name:
</td><td colspan=4><input name="templatename"  type="text" size=20 maxlength=80 tabindex="3"></td></tr>
<tr ><td nowrap class="dataLabel">Description:
</td><td colspan=4><input name="description" "p8" type="text" size=65 maxlength=255 tabindex="5"></td></tr>
<tr ><td nowrap class="dataLabel"><font class="required">*</font>Subject:
</td><td colspan=4><input name="subject"  type="text" size=65 maxlength=100 tabindex="6"></td></tr>
<tr ><td nowrap valign=top class="dataLabel">Email Body:
</td><td valign=top colspan=4><TEXTAREA wrap="SOFT" NAME="body" ROWS="10" COLS="70" tabindex="7"></TEXTAREA></td></tr>
</table>
<TABLE WIDTH="90%" CELLPADDING="0" CELLSPACING="5" BORDER="0">
                <TD NOWRAP>&nbsp;<input type="submit" name="save" value=" Save " class="button" >&nbsp;<input type="submit" name="cancel" value="Cancel" class="button" ></TD>
              </TR>
        </TABLE>
</form>
</body>
</html>









