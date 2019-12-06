<?php

namespace Ganguo\Taobao;

class AliqinFc extends Client
{
    /**
     * 短信发送
     * https://api.alidayu.com/doc2/apiDetail?spm=a3142.8063005.3.1.64159693N5VqXd&apiId=25450.
     *
     * @param string $phone
     * @param string $sign_name
     * @param string $template_code
     * @param array  $param
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function smsNumSend($phone = '', $sign_name = '', $template_code = '', $param = [])
    {
        $method = 'alibaba.aliqin.fc.sms.num.send';
        $res = $this->post($method, [
            'sms_type' => 'normal',
            'sms_free_sign_name' => $sign_name,
            'rec_num' => $phone,
            'sms_template_code' => $template_code,
            'sms_param' => json_encode($param),
        ]);
        if (!isset($res->alibaba_aliqin_fc_sms_num_send_response)) {
            switch ($res->error_response->sub_code) {
                case 'isv.BUSINESS_LIMIT_CONTROL':
                    /*
                     * 短信验证码，使用同一个签名，对同一个手机号码发送短信验证码，
                     * 支持1条/分钟，5条/小时，10条/天。
                     * 一个手机号码通过阿里大于平台只能收到40条/天。
                     * 短信通知，使用同一签名、同一模板，对同一手机号发送短信通知，允许每天50条（自然日）。
                     */
                    throw new \Exception('短信发送过于频繁');
                    break;
                default:
                    throw new \Exception($res->error_response->sub_msg);
            }
        }

        return $res->alibaba_aliqin_fc_sms_num_send_response;
    }
}
