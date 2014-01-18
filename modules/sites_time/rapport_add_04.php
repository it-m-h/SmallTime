<Form action="?action=insert_rapport&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>" method="post" target="_self">
<table width="100%" border="0" cellpadding="5" cellspacing="2">
 <tr>
  <td class=td_background_tag align=left>
         <textarea cols="82" rows="10" name="rapport"><?php echo $_rapport->get_rapport($_user->_ordnerpfad, $_time->_timestamp); ?></textarea>
                    <!--    <script type="text/javascript">
                                CKEDITOR.replace( 'rapport' );
                        </script>   !-->
  </td>
 </tr>
 <tr>
  <td class=td_background_top>
  <input type="submit" name="absenden" value="UPDATE" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" name="absenden" value="CANCEL" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type='submit'  name='absenden' value='DELETE' >
  </td>
 </tr>
  <tr>
  <td align=left>
  </td>
 </tr>
</table>
</Form>