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
namespace TencentCloud\Cbs\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method integer getTotalCount() 获取符合条件的云硬盘数量。
 * @method void setTotalCount(integer $TotalCount) 设置符合条件的云硬盘数量。
 * @method array getDiskSet() 获取云硬盘的详细信息列表。
 * @method void setDiskSet(array $DiskSet) 设置云硬盘的详细信息列表。
 * @method string getRequestId() 获取唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 */

/**
 *DescribeDisks返回参数结构体
 */
class DescribeDisksResponse extends AbstractModel
{
    /**
     * @var integer 符合条件的云硬盘数量。
     */
    public $TotalCount;

    /**
     * @var array 云硬盘的详细信息列表。
     */
    public $DiskSet;

    /**
     * @var string 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;
    /**
     * @param integer $TotalCount 符合条件的云硬盘数量。
     * @param array $DiskSet 云硬盘的详细信息列表。
     * @param string $RequestId 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
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
        if (array_key_exists("TotalCount",$param) and $param["TotalCount"] !== null) {
            $this->TotalCount = $param["TotalCount"];
        }

        if (array_key_exists("DiskSet",$param) and $param["DiskSet"] !== null) {
            $this->DiskSet = [];
            foreach ($param["DiskSet"] as $key => $value){
                $obj = new Disk();
                $obj->deserialize($value);
                array_push($this->DiskSet, $obj);
            }
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
