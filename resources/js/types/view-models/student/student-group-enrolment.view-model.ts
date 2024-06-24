export interface StudentGroupEnrolmentViewModel {
    key: string;
    studentGroupKey: string;
    studentGroupName: string;
    studentGroupAncestors: { key: string; name: string }[];
    institutionKey: string;
    institutionName: string;
    institutionPictureUri: string;
    institutionAncestors: { key: string; name: string }[];
}
