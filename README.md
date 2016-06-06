# FileManager

FileManager是一个基于PHP Laravel的Web文件管理器，支持基础文件操作。
![image](https://s3-us-west-2.amazonaws.com/assets.cdn.univerch.com/filemanager.png)

## 服务器要求

 * PHP >= 5.4
 * composer
 * npm

## 基础功能

 * 基础用户验证
 * 自定义文件系统物理路径
 * 文件（夹）创建、上传、下载、重命名、删除
 * AJAX支持

## 在线演示

 * 测试地址（暂时下线）
 * 测试用户：test@test.local
 * 密码：123456

## 安装

 1. 克隆Git仓库

     ```
     git clone https://github.com/lzhang43/FileManager.git
     ```

 2. 安装依赖库

     ```
     composer install
     npm install
     ```

 3. 编译CSS、JS文件

    ```
    gulp
    ```

## 配置

  1. 在程序根目录下，复制环境变量配置文件

     ```
     cp .env.example .env
     ```

  2. 修改环境变量

     ```
     DISK_NAME=local     // 默认“磁盘”名称为local
     DISK_DRIVER=local   // 默认使用本地文件系统，也通过Laravel支持 Amazon S3 和 RackSpace
     DISK_ROOT=/var/disk // 可自定义“磁盘”根目录，默认情况下使用程序目录下 storage/app 目录作为根目录

     DB_HOST=localhost      // 数据库地址
     DB_DATABASE=homestead  // 数据库名称
     DB_USERNAME=hoemstead  // 数据库用户名
     DB_PASSWORD=secret     // 数据库密码
     ```

  3. 安装数据库

     ```
     php artisan migrate
     ```

  4. 安装完毕后即可通过Web浏览器访问使用
