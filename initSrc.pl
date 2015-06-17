#!/usr/bin/perl

my $dir = $ARGV[0];
print $dir;
my @otherDirs = ('vip','www','admin','cms','crm');
my @srcName = ('js','css','img');
foreach(@otherDirs){
	foreach $name(@srcName){
		#print $name." ".$_;
		system("mkdir -p $dir/$_/$name");
		system("cp /git/code/cms/.gitignore $dir/$_/$name/.gitignore");
	}
}
