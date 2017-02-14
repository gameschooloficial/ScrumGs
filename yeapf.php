<?php

              /*
              * yeapf.php
              * (C) 2004-2014 Esteban Daniel Dortta (dortta@yahoo.com)
              */

              function _yLoaderDie($reconfigureLinkEnabled)
              {
                global $callback;
                $script=basename($_SERVER["PHP_SELF"]);
                $isXML=(strpos("query.php",$script)!==false);
                $isJSON=(strpos("rest.php",$script)!==false);
                $isCLI=(php_sapi_name() == "cli");

                if (file_exists("flags/flag.dbgloader")) error_log(basename(__FILE__)." ".date("i:s").": app dying\n",3,"logs/yeapf.loader.log");

                $args=func_get_args();
                if (func_num_args()>1)
                  array_shift($args);
                if ((!$isXML) && (!$isCLI))
                  $msg=join("<br>",$args);
                else
                  $msg=join("\n",$args);
                $lineMsg=$args;
                $lineMsg = str_replace("<br>",";\t",$lineMsg);
                $lineMsg = str_replace("\n",". ",$lineMsg);
                syslog(LOG_INFO,$script." (running at ".getcwd().")"." - ".$lineMsg);

                if (function_exists("_die")) {
                  if ($isCLI)
                    _die(htmlentities($lineMsg));
                  else
                    _die($lineMsg);
                } else {
                  if (!$isXML) {
                    if ($isCLI) {
                      echo "\n$msg\n";
                    } else if ($isJSON) {
                      echo "_dump('$lineMsg');\n";
                      if ((is_string($callback)) && (trim($callback)>""))
                        echo "if (typeof $callback == 'function') $callback(null);";
                    } else {
                      header("Content-Type: text/html; charset=UTF-8");
                      echo "<style> .err {background-color:#FFC0CB; border-style:solid; border-width:2px; border-color:#FF0000;margin:32px;padding:32px;border-radius:4px}</style>";
                      if ($reconfigureLinkEnabled)
                      $doConfig="<div>Click <b><a href=\"configure.php\">here</a></b> to configure<br></div>";
                      $copyrightNote="<small><em>YeAPF (C) 2004-2014 Esteban Dortta</em></small>";
                      echo "<div class=err><div>$msg</div>$doConfig$copyrightNote</div>";
                    }
                  }
                }
                die("");
              }

              function _yeapf_getFileValue($fileName)
              {
                $aux1=$aux2=0;
                if (file_exists($fileName))
                  $aux1=join("",file($fileName));
                if (file_exists("flags/".$fileName))
                  $aux2=join("",file("flags/".$fileName));
                $ret=intval($aux1) | intval($aux2);
                return $ret;
              }

              if (file_exists("flags/timezone"))
                $auxDefaultTimeZone=file_get_contents("flags/timezone");
              else
                $auxDefaultTimeZone = @date_default_timezone_get();
              
              date_default_timezone_set($auxDefaultTimeZone);


              if (file_exists("flags/flag.dbgloader")) error_log(basename(__FILE__)." ".date("i:s").": preparing flags\n",3,"logs/yeapf.loader.log");

              $yeapfLogFlags=_yeapf_getFileValue("debug.flags");
              $yeapfLogLevel=_yeapf_getFileValue("debug.level");
              $yeapfLogBacktrace=_yeapf_getFileValue("debug.trace");
              $yeapfDebugAll=intval(file_exists("flags/flag.dbgphp"))?1:0;
              $yeapfPauseAfterClickFlag=_yeapf_getFileValue("flags/flag.pause");

              $logOutput=0;  // default is to not produce debug output

              if ($yeapfDebugAll) {
                ini_set("display_errors","1");
                error_reporting (E_ALL);
              }

              if (file_exists("flags/development.debug"))
               $developmentStage = join("",file("flags/development.debug"));

              $jsDumpEnabled = intval(file_exists("flags/debug.javascript")) || isset($jsDumpEnabled)?intval($jsDumpEnabled):0;
              $aDebugIP = trim(file_exists("flags/debug.ip")?join(file("flags/debug.ip")):"");

              if (file_exists("flags/flag.dbgloader")) error_log(basename(__FILE__)." ".date("i:s").": loading config files\n",3,"logs/yeapf.loader.log");

              if (file_exists(dirname(__FILE__)."/.config/yeapf.config"))
                (@include_once dirname(__FILE__)."/.config/yeapf.config") || _yLoaderDie(true, dirname(__FILE__)."Error loading /.config/yeapf.config");
              else
                _yLoaderDie(true, dirname(__FILE__)."/.config/yeapf.config not found");

              $__yeapfPath=$yeapfConfig["yeapfPath"];
              $__yeapfContext=$yeapfConfig["context"];
              $__yeapfCWD = getcwd();
              $__yeapfCWD = str_replace("\\","/",$__yeapfCWD);
              if ($__yeapfContext != $__yeapfCWD) _yLoaderDie(true,"YeAPF running out of original context or is missconfigured\n * $__yeapfCWD differs from $__yeapfContext");

              $auxAppFolderName="";
              if (file_exists("appFolderName.def"))
                $auxAppFolderName="appFolderName.def";

              if ($auxAppFolderName>"") {
                $appFolder=file($auxAppFolderName);
                while (count($appFolder)<3)
                  $appFolder[count($appFolder)]="";
                $appFolderName=$appFolder[0];
                $appFolderRights=intval($appFolder[1]);
                $appFolderInsecureEvents=trim($appFolder[2]);
              } else {
                $appFolderName=basename(getcwd());
                $appFolderRights=0;
                // md5("*.") = "3db6003ce6c1725a9edb9d0e99a9ac3d"
                $appFolderInsecureEvents="3db6003ce6c1725a9edb9d0e99a9ac3d";
              }
              // in case cfgInitialVerb is defined, we need to put this verb as insecure
              if (isset($cfgInitialVerb)) {
                if ($appFolderInsecureEvents>"")
                  $appFolderInsecureEvents.=",";
                $appFolderInsecureEvents.=md5($cfgInitialVerb.".");
              }
              unset($auxAppFolderName);
              if (file_exists("flags/flag.dbgloader")) error_log(basename(__FILE__)." ".date("i:s").": loading $__yeapfPath/yeapf.functions.php\n",3,"logs/yeapf.loader.log");
              (@include_once $__yeapfPath."/yeapf.functions.php") || (_yLoaderDie("$__yeapfPath/yeapf.functions.php not found"));

              if (!function_exists("decimalMicrotime")) die("decimalMicrotime() required");

              $t0=decimalMicrotime();

              if (file_exists("flags/flag.dbgloader")) error_log(basename(__FILE__)." ".date("i:s").": verifiyng yeapf version\n",3,"logs/yeapf.loader.log");

              _recordWastedTime("Checking YeAPF Version");

              if (function_exists("yeapfVersion"))
                if (("%"."YEAPF_VERSION%")==yeapfVersion()) {
                  error_log(basename(__FILE__)." ".date("i:s").": WARNING: Using developer version\n",3,"logs/yeapf.loader.log");
                } else {
                  if (("0.8.54") != yeapfVersion())
                    _yLoaderDie(true, "Your configure version is '0.8.54' while your installed version is '".yeapfVersion()."'");
                }
              if (!isset($appName))
                $appName = "dummy";
              $yeapfConfig["searchPath"]=$appName.";".$yeapfConfig["searchPath"];
              set_include_path(get_include_path().":".str_replace(";",":",$yeapfConfig["searchPath"]));
              
              _recordWastedTime("Checking distribution");
              $md5Files=array("body.php", "index.php", "configure.php", "search.path");
              $configMD5="";
              foreach($md5Files as $aFileName)
                if (file_exists($aFileName))
                  $configMD5.=join("", file($aFileName));
              $configMD5=md5($configMD5);

              $savedConfigMD5 = join("",file("configure.md5"));
              if ((file_exists("configure.php")) && ($configMD5 != $savedConfigMD5)) {
                _yLoaderDie(true, "YeAPF not configured\nRun <a href=\"configure.php\">configure.php</a> again.");
              }
              
              if (file_exists("flags/flag.dbgloader")) error_log(basename(__FILE__)." ".date("i:s").": loading application script\n",3,"logs/yeapf.loader.log");
              // load application script
              $appWD=basename(getcwd());
              // drop version info as in "customers-3.5" keeping with "costumers"
              $appWD=substr($appWD,0,strpos($appWD."-","-"));
              $__scriptList = array("$appWD.php", "$appWD.rules.php",
                                    "$appName.php", "$appName.$appWD.php",
                                    "$appName.rules.php", "$appName.$appWD.rules.php", "rules.php",
                                    bestName("$appName.security.php"),
                                    "includes/security.php");
              _recordWastedTime("Loading app libraries");
              $t1=decimalMicrotime();
              foreach($__scriptList as $__scriptName) {
                $__scriptName = bestName($__scriptName);
                if ((file_exists($__scriptName)) && (!is_dir($__scriptName))) {
                  _recordWastedTime("Loading $__scriptName");

                  (@include_once "$__scriptName") or _yLoaderDie(true, dirname(__FILE__)."Error loading $__scriptName");
                }
              }
              $t2=decimalMicrotime()-$t1;
              _recordWastedTime("App libraries loaded ($t2)");

              
          _dumpY(1,1,'yeapf loaded');


              yeapfStage("click");
              yeapfStage("registerAppEvents_$appWD");
              yeapfStage("registerAppEvents");
              
              $t0=decimalMicrotime()-$t0;
              _recordWastedTime("overall loader wasted time: $t0");
              ?>