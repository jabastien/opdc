<?php

require_once "CactiCommand.php";

/**
 * Test the CactiCommand class
 */
class CactiCommandTest extends PHPUnit_Framework_TestCase
{
    private $mockStatusDataJson = '{"devstatus": {
"Sys_Time": 1361724623,
"Sys_Batt_V": 18.5,
"ports": [
{ "Port": 1, "Dev": "FX","Type": "230V","Inv_I": 0,"Chg_I": 0,"Buy_I": 0,"Sell_I": 0,"VAC_in": 228,"VAC_out": 226,"Batt_V": 18.4,"AC_mode": "AC USE","INV_mode": "Charger Off","Warn": ["none"],"Error": ["none"],"AUX": "disabled"},
{ "Port": 2, "Dev": "CC","Type": "FM","Out_I": 0.0,"In_I": 0,"Batt_V": 18.8,"In_V": 89.8,"Out_kWh": 0.0,"Out_AH": 0,"CC_mode": "Silent","Error": ["none"],"Aux_mode": "Vent Fan","AUX": "disabled"},
{ "Port": 4, "Dev": "FNDC","Enabled": ["A","B"],"Shunt_A_I": -0.4,"Shunt_A_AH": -82,"Shunt_A_kWh":  -1.960,"Shunt_B_I":  0.0,"Shunt_B_AH": 37,"Shunt_B_kWh":  0.990,"SOC": 60,"Min_SOC": 60,"Days_since_full": 2.1,"CHG_parms_met": false,"In_AH_today": 2,"Out_AH_today": 20,"In_kWh_today":  0.030,"Out_kWh_today":  0.450,"Net_CFC_AH": -48,"Net_CFC_kWh":  -1.030,"Batt_V": 18.5,"Batt_temp": "###","Aux_mode": "auto","AUX": "disabled"}
]}}';
    private $expectedDataPort1 = 'Port1_VAC_in:228 Port1_VAC_out:226 Port1_Batt_V:18.4';
    private $expectedDataPort2 = 'Port2_Out_I:0 Port2_In_I:0 Port2_Batt_V:18.8 Port2_In_V:89.8 Port2_Out_kWh:0 Port2_Out_AH:0';
    private $expectedDataPort4 = 'Port4_Shunt_A_I:-0.4 Port4_Shunt_A_AH:-82 Port4_Shunt_A_kWh:-1.96 Port4_Shunt_B_I:0 Port4_Shunt_B_AH:37 Port4_Shunt_B_kWh:0.99 Port4_SOC:60 Port4_Min_SOC:60 Port4_Days_since_full:2.1 Port4_In_AH_today:2 Port4_Out_AH_today:20 Port4_In_kWh_today:0.03 Port4_Out_kWh_today:0.45 Port4_Net_CFC_AH:-48 Port4_Net_CFC_kWh:-1.03 Port4_Batt_V:18.5';

    /**
     * covers CactiCommand::parseBattData()
     */
    public function testParseBattData()
    {
        $expected         = 'today_min_batt:18.2 today_min_batt_time:1361724811 today_max_batt:23.8 today_max_batt_time:1361664056 sys_min_batt:0 sys_min_batt_time:1262304005 sys_max_batt:29 sys_max_batt_time:1361548903';
        $mockBattDataJson = '{"sys_battery": {"today_min_batt": 18.2,"today_min_batt_time": 1361724811,"today_max_batt": 23.8,"today_max_batt_time": 1361664056,"sys_min_batt": 0.0,"sys_min_batt_time": 1262304005,"sys_max_batt": 29.0,"sys_max_batt_time": 1361548903}}';


        $cactiCommand = new CactiCommand();
        $actual       = $cactiCommand->parseBattData(json_decode($mockBattDataJson, true));

        $this->assertEquals($expected, implode(' ', $actual));
    }


    /**
     * test parseStatusPort()
     *
     * @param string $expected exptected data
     * @param int    $portNum  port number on the device
     * @param array  $allowed  allowed fields
     * @param array  $data     data from the device
     *
     * @covers CactiCommand:parseStatusPort
     * @dataProvider parseStatusPortProvider
     */
    public function testParseStatusPort($expected, $portNum, array $allowed, array $data)
    {
        $cactiCommand = new CactiCommand();
        $actual       = $cactiCommand->parseStatusPort($portNum, $allowed, $data);
        $this->assertEquals($expected, implode(' ', $actual));
    }

    /**
     * data provider for testParseStatusPort
     *
     * @return array
     */
    public function parseStatusPortProvider()
    {
        $data = json_decode($this->mockStatusDataJson, true);

        return [
            [
                $this->expectedDataPort1,
                1,
                ['VAC_in', 'VAC_out', 'Batt_V'],
                $data['devstatus']['ports'][0]
            ], [
                $this->expectedDataPort2,
                2,
                ['Out_I', 'In_I', 'Batt_V', 'In_V', 'Out_kWh', 'Out_AH'],
                $data['devstatus']['ports'][1]
            ], [
                $this->expectedDataPort4,
                4,
                ['Shunt_A_I', 'Shunt_A_AH', 'Shunt_A_kWh', 'Shunt_B_I', 'Shunt_B_AH', 'Shunt_B_kWh', 'SOC', 'Min_SOC', 'Days_since_full', 'In_AH_today', 'Out_AH_today', 'In_kWh_today', 'Out_kWh_today', 'Net_CFC_AH', 'Net_CFC_kWh', 'Batt_V'],
                $data['devstatus']['ports'][2]
            ]


        ];
    }

    /**
     * covers CactiCommand::parseBattData()
     */
    public function testParseStatusData()
    {
        $expected = "Sys_Batt_V:18.5 {$this->expectedDataPort1} {$this->expectedDataPort2} {$this->expectedDataPort4}";

        $cactiCommand = new CactiCommand();
        $actual       = $cactiCommand->parseStatusData(json_decode($this->mockStatusDataJson, true));

        $this->assertEquals($expected, implode(' ', $actual));
    }
}
