#!/usr/bin/perl

my $dir = $ARGV[0];
print $dir;
my @arDir = ('controllers','views','libs','config');
foreach(@arDir){
	system("mkdir -p $dir/$_");	
	system("cp /git/code/cms/.gitignore $dir/$_/.gitignore");
}
