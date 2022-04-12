#!/bin/sh

version='1.0.7'

# put files in proper directory for DEB file generation
basename='remote-dconf'
dirname="$basename"_"$version"_amd64
mkdir -p $dirname/opt/$basename $dirname/etc/systemd/system
cp -pr --parents index.php lib start.sh $dirname/opt/$basename
cp -pr etc/$basename.service $dirname/etc/systemd/system
cp -pr DEBIAN $dirname
chmod 755 $dirname/DEBIAN
sed -i s/VERSION/$version/ $dirname/DEBIAN/control

# make the deb file
dpkg-deb --build --root-owner-group $dirname

# cleanup
rm -rf $dirname
