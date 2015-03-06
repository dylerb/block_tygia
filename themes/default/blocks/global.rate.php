<?php
/**
* @Project NUKEVIET 4
* @Author ConVoi (convoi@gmail.com
* @Copyright (C) 2015 HienTuong Web & Net. All rights reserved
* @Createdate 2/15/2015 12:00
* @Credit: anhtu@vinades.vn
*/
if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
/**
* my_formatMoney()
*
* @param mixed $number
* @param bool $fractional
* @return
*/
function my_formatMoney( $number, $fractional = false )
{
if ( $fractional )
{
$number = sprintf( '%.2f', $number );
}
while ( true )
{
$replaced = preg_replace( '/(-?\d+)(\d\d\d)/', '$1,$2', $number );
if ( $replaced != $number )
{
$number = $replaced;
}
else
{
break;
}
}
return $number;
}
$content = "";
$_dt = array();
$cacheLoadFile = NV_ROOTDIR . '/cache/_vietcombank.cache';
$reload = false;
if ( ! file_exists( $cacheLoadFile ) )
{
$reload = true;
}
elseif ( filemtime( $cacheLoadFile ) < NV_CURRENTTIME - 3600 )
{
$reload = true;
}
if ( $reload )
{
$_Ct = "";
$url = "http://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx";
$xml = simpleXML_load_file( $url, "SimpleXMLElement", LIBXML_NOCDATA );
if ( $xml !== false )
{
$xml = nv_object2array( $xml );
$_dt['upd'] = trim( $xml['DateTime'] );
$_dt['upd'] = date( "c", strtotime( $_dt['upd'] ) );
$_dt['upd'] = substr( $_dt['upd'], 0, -6 ) . "+07:00";
$_dt['upd'] = strtotime( $_dt['upd'] );
$_dt['rates'] = array();
foreach ( $xml['Exrate'] as $k )
{
$_dt['rates'][$k['@attributes']['CurrencyCode']] = array( //
'CurrencyCode' => strtoupper( $k['@attributes']['CurrencyCode'] ), //
'CurrencyName' => ucwords( strtolower( $k['@attributes']['CurrencyName'] ) ), //
'Buy' => my_formatMoney( $k['@attributes']['Buy'], 1 ), //
'Transfer' => my_formatMoney( $k['@attributes']['Transfer'], 1 ), //
'Sell' => my_formatMoney( $k['@attributes']['Sell'], 1 ) //
);
}
$_Ct = serialize( $_dt );
file_put_contents( $cacheLoadFile, $_Ct, LOCK_EX );
}
}
else
{
$_Ct = file_get_contents( $cacheLoadFile );
if ( ! empty( $_Ct ) )
{
$_dt = unserialize( $_Ct );
}
}
if ( ! empty( $_dt ) )
{
if ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/blocks/global.rate.tpl" ) )
{
$block_theme = $global_config['site_theme'];
}
else
{
$block_theme = "default";
}
$xtpl = new XTemplate( "global.rate.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/blocks" );
if(!isset($lang_global['CurrencyCode'])) $lang_global['CurrencyCode'] = "Code";
if(!isset($lang_global['buy'])) $lang_global['buy'] = "Buy";
if(!isset($lang_global['transfer'])) $lang_global['transfer'] = "Transfer";
if(!isset($lang_global['sell'])) $lang_global['sell'] = "Sell";
$xtpl->assign( 'LANG', $lang_global );
$xtpl->assign( 'UPDATE', nv_date( "j/m/Y, G:i", $_dt['upd'] ) );
$i = 0;
foreach ( $_dt['rates'] as $row )
{
if ( $row['Buy'] == "0.00" ) $row['Buy'] = "-";
if ( $row['Transfer'] == "0.00" ) $row['Transfer'] = "-";
if ( $row['Sell'] == "0.00" ) $row['Sell'] = "-";
$row['class'] = $i % 2 == 0 ? "" : " class=\"second\"";
$xtpl->assign( 'LOOP', $row );
$xtpl->parse( 'main.loop' );
++$i;
}
$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
}
?>
