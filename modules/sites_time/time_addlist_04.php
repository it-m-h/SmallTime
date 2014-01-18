<Form name="insert" action="?action=insert_time_list&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>" method="post" target="_self">
<table width="100%" border="0" cellpadding="5" cellspacing="2">
 <tr>
  <td class=td_background_wochenende width="200" align=left>Datum : (Tag / Monat / Jahr)</td>
  <td class=td_background_tag align=left>
                  <input type="text" name="_w_tag" value="<?php echo $_time->_tag; ?>" size="4">
                  <input type="text" name="_w_monat" value="<?php echo $_time->_monat; ?>" size="4">
                  <input type="text" name="_w_jahr" value="<?php echo $_time->_jahr; ?>" size="4">
  </td>
 </tr>
 
 <tr>
  <td class=td_background_wochenende width="200" align=left>Mehrere Zeitangaben : z.B</td>
  <td class=td_background_wochenende align=left>7.51-12.05-13-16.20</td>
 </tr>
 
 <tr >
  <td class=td_background_wochenende align=left>Eingabe:</td>
  <td class=td_background_tag align=left>
      <input type="text" name="_zeitliste" value="" size="74">
  </td>
 </tr>
 <tr>
  <td class=td_background_top>&nbsp;</td>
  <td class=td_background_top align=left><input type="submit" name="absenden" value="OK" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type='submit'  name='absenden' value='CANCEL' ></td>
 </tr>


</table>
</Form>