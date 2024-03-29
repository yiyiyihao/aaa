<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Cloudaudit\V20190319\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method integer getEndTime() 获取结束时间
 * @method void setEndTime(integer $EndTime) 设置结束时间
 * @method integer getStartTime() 获取开始时间
 * @method void setStartTime(integer $StartTime) 设置开始时间
 * @method array getLookupAttributes() 获取检索条件
 * @method void setLookupAttributes(array $LookupAttributes) 设置检索条件
 * @method integer getIsApiV3() 获取是否调用新的查询接口
 * @method void setIsApiV3(integer $IsApiV3) 设置是否调用新的查询接口
 * @method integer getMaxResults() 获取返回日志的最大条数
 * @method void setMaxResults(integer $MaxResults) 设置返回日志的最大条数
 * @method string getNextToken() 获取查看更多日志的凭证
 * @method void setNextToken(string $NextToken) 设置查看更多日志的凭证
 */

/**
 *LookUpEvents请求参数结构体
 */
class LookUpEventsRequest extends AbstractModel
{
    /**
     * @var integer 结束时间
     */
    public $EndTime;

    /**
     * @var integer 开始时间
     */
    public $StartTime;

    /**
     * @var array 检索条件
     */
    public $LookupAttributes;

    /**
     * @var integer 是否调用新的查询接口
     */
    public $IsApiV3;

    /**
     * @var integer 返回日志的最大条数
     */
    public $MaxResults;

    /**
     * @var string 查看更多日志的凭证
     */
    public $NextToken;
    /**
     * @param integer $EndTime 结束时间
     * @param integer $StartTime 开始时间
     * @param array $LookupAttributes 检索条件
     * @param integer $IsApiV3 是否调用新的查询接口
     * @param integer $MaxResults 返回日志的最大条数
     * @param string $NextToken 查看更多日志的凭证
     */
    function __construct()
    {

    }
    /**
     * 内部实现，用户禁止调用
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }

        if (array_key_exists("StartTime",$param) and $param["StartTime"] !== null) {
            $this->StartTime = $param["StartTime"];
        }

        if (array_key_exists("LookupAttributes",$param) and $param["LookupAttributes"] !== null) {
            $this->LookupAttributes = [];
            foreach ($param["LookupAttributes"] as $key => $value){
                $obj = new LookupAttribute();
                $obj->deserialize($value);
                array_push($this->LookupAttributes, $obj);
            }
        }

        if (array_key_exists("IsApiV3",$param) and $param["IsApiV3"] !== null) {
            $this->IsApiV3 = $param["IsApiV3"];
        }

        if (array_key_exists("MaxResults",$param) and $param["MaxResults"] !== null) {
            $this->MaxResults = $param["MaxResults"];
        }

        if (array_key_exists("NextToken",$param) and $param["NextToken"] !== null) {
            $this->NextToken = $param["NextToken"];
        }
    }
}
